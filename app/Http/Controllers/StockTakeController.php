<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Location;
use App\Models\Notification;
use App\Models\StockTake;
use App\Models\StockTakeItem;
use App\Models\ActivityLog;
use App\Models\ItemMovement;
use Illuminate\Http\Request;

class StockTakeController extends Controller
{
    /**
     * Daftar semua sesi stok opname.
     */
    public function index(Request $request)
    {
        $query = StockTake::with(['location', 'startedByUser'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }

        $stockTakes = $query->paginate(15)->withQueryString();

        return view('stock-take.index', compact('stockTakes'));
    }

    /**
     * Form buat sesi stok opname baru.
     */
    public function create()
    {
        $locations = Location::orderBy('name')->get();

        // Hitung preview jumlah item per lokasi
        $locationItemCounts = Item::selectRaw('location_id, count(*) as total')
            ->groupBy('location_id')
            ->pluck('total', 'location_id');

        $totalItems = Item::count();

        return view('stock-take.create', compact('locations', 'locationItemCounts', 'totalItems'));
    }

    /**
     * Simpan sesi stok opname baru + auto-populate items.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location_id' => 'nullable|exists:locations,id',
        ]);

        $stockTake = StockTake::create([
            'title' => $request->title,
            'description' => $request->description,
            'location_id' => $request->location_id,
            'started_by' => auth()->id(),
            'status' => 'draft',
        ]);

        // Auto-populate items berdasarkan lokasi
        $itemsQuery = Item::query();
        if ($request->location_id) {
            $itemsQuery->where('location_id', $request->location_id);
        }

        $items = $itemsQuery->get();

        foreach ($items as $item) {
            StockTakeItem::create([
                'stock_take_id' => $stockTake->id,
                'item_id' => $item->id,
                'system_quantity' => $item->quantity,
                'system_condition' => $item->condition,
            ]);
        }

        // Log activity
        ActivityLog::record('created', "Membuat sesi Stok Opname \"{$stockTake->code}\" dengan {$items->count()} item", $stockTake);

        return redirect()->route('stock-take.show', $stockTake->id)
            ->with('success', "Sesi Stok Opname {$stockTake->code} berhasil dibuat dengan {$items->count()} item.");
    }

    /**
     * Detail sesi stok opname + daftar items.
     */
    public function show(Request $request, $id)
    {
        $stockTake = StockTake::with(['location', 'startedByUser', 'approvedByUser'])->findOrFail($id);

        $query = $stockTake->items()->with(['item.category', 'item.location', 'checkedByUser']);

        // Filter
        if ($request->filled('filter')) {
            switch ($request->filter) {
                case 'unchecked':
                    $query->whereNull('actual_quantity');
                    break;
                case 'match':
                    $query->whereNotNull('actual_quantity')->where('difference', 0);
                    break;
                case 'discrepancy':
                    $query->whereNotNull('actual_quantity')->where('difference', '!=', 0);
                    break;
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('item', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $stockTakeItems = $query->paginate(25)->withQueryString();

        // Statistics
        $stats = [
            'total' => $stockTake->total_items_count,
            'checked' => $stockTake->checked_count,
            'match' => $stockTake->match_count,
            'short' => $stockTake->short_count,
            'over' => $stockTake->over_count,
            'progress' => $stockTake->progress_percent,
        ];

        return view('stock-take.show', compact('stockTake', 'stockTakeItems', 'stats'));
    }

    /**
     * Update quantity/kondisi aktual per item (AJAX).
     */
    public function updateItem(Request $request, $stockTakeId, $itemId)
    {
        $stockTake = StockTake::findOrFail($stockTakeId);

        // Hanya bisa update saat status draft atau in_progress
        if (!in_array($stockTake->status, ['draft', 'in_progress'])) {
            return response()->json(['error' => 'Sesi stok opname sudah tidak bisa diubah.'], 422);
        }

        $request->validate([
            'actual_quantity' => 'required|integer|min:0',
            'actual_condition' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:500',
        ]);

        $stockTakeItem = StockTakeItem::where('stock_take_id', $stockTakeId)
            ->where('item_id', $itemId)
            ->firstOrFail();

        $stockTakeItem->update([
            'actual_quantity' => $request->actual_quantity,
            'actual_condition' => $request->actual_condition,
            'difference' => $request->actual_quantity - $stockTakeItem->system_quantity,
            'notes' => $request->notes,
            'checked_by' => auth()->id(),
            'checked_at' => now(),
        ]);

        // Auto-start jika masih draft
        if ($stockTake->status === 'draft') {
            $stockTake->update([
                'status' => 'in_progress',
                'started_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'difference' => $stockTakeItem->difference,
            'difference_label' => $stockTakeItem->difference_label,
            'difference_color' => $stockTakeItem->difference_color,
            'checked_by' => auth()->user()->name,
            'checked_at' => now()->format('d/m/Y H:i'),
        ]);
    }

    /**
     * Selesaikan sesi stok opname.
     */
    public function complete($id)
    {
        $stockTake = StockTake::findOrFail($id);

        if (!in_array($stockTake->status, ['draft', 'in_progress'])) {
            return back()->with('error', 'Sesi tidak bisa diselesaikan dari status saat ini.');
        }

        // Hitung ringkasan
        $total = $stockTake->total_items_count;
        $checked = $stockTake->checked_count;
        $match = $stockTake->match_count;
        $short = $stockTake->short_count;
        $over = $stockTake->over_count;

        $summary = "Dari {$total} item: {$checked} dicek, {$match} cocok, {$short} kurang, {$over} lebih.";
        if ($checked < $total) {
            $unchecked = $total - $checked;
            $summary .= " {$unchecked} item belum dicek.";
        }

        $stockTake->update([
            'status' => 'completed',
            'completed_at' => now(),
            'notes_summary' => $summary,
        ]);

        // Kirim notifikasi ke Superadmin untuk approval
        Notification::sendToRole('Superadmin', 'info',
            'Stok Opname Menunggu Persetujuan',
            "Sesi {$stockTake->code} \"{$stockTake->title}\" telah selesai dan menunggu persetujuan Anda. {$summary}",
            route('stock-take.show', $stockTake->id),
            'fact_check'
        );

        ActivityLog::record('updated', "Menyelesaikan Stok Opname \"{$stockTake->code}\"", $stockTake);

        return back()->with('success', 'Stok Opname berhasil diselesaikan dan menunggu persetujuan Superadmin.');
    }

    /**
     * Approve sesi stok opname (Superadmin only).
     */
    public function approve($id)
    {
        $stockTake = StockTake::findOrFail($id);

        if ($stockTake->status !== 'completed') {
            return back()->with('error', 'Hanya sesi yang sudah selesai yang bisa disetujui.');
        }

        // Update quantity items berdasarkan hasil stock take
        foreach ($stockTake->items as $stockTakeItem) {
            if ($stockTakeItem->actual_quantity !== null) {
                $item = $stockTakeItem->item;
                $oldQuantity = $item->quantity;
                $item->update(['quantity' => $stockTakeItem->actual_quantity]);

                // Update kondisi jika ada perubahan
                if ($stockTakeItem->actual_condition && $stockTakeItem->actual_condition !== $stockTakeItem->system_condition) {
                    $item->update(['condition' => $stockTakeItem->actual_condition]);
                }

                // Catat pergerakan jika ada selisih
                $difference = $stockTakeItem->difference;
                if ($difference !== 0) {
                    ItemMovement::create([
                        'item_id' => $item->id,
                        'user_id' => auth()->id(),
                        'type' => $difference > 0 ? 'masuk' : 'keluar',
                        'quantity' => abs($difference),
                        'notes' => "Penyesuaian stok dari Stock Take {$stockTake->code} (selisih: {$difference})",
                        'movement_date' => now(),
                    ]);
                }
            }
        }

        $stockTake->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // Notifikasi ke pembuat sesi
        Notification::send($stockTake->started_by, 'success',
            'Stok Opname Disetujui',
            "Sesi {$stockTake->code} \"{$stockTake->title}\" telah disetujui oleh " . auth()->user()->name . ".",
            route('stock-take.show', $stockTake->id),
            'verified'
        );

        ActivityLog::record('updated', "Menyetujui Stok Opname \"{$stockTake->code}\"", $stockTake);

        return back()->with('success', 'Stok Opname berhasil disetujui dan quantity barang telah diperbarui.');
    }

    /**
     * Hapus sesi stok opname (hanya draft).
     */
    public function destroy($id)
    {
        $stockTake = StockTake::findOrFail($id);

        if ($stockTake->status !== 'draft') {
            return back()->with('error', 'Hanya sesi dengan status Draft yang bisa dihapus.');
        }

        $code = $stockTake->code;
        $stockTake->delete();

        ActivityLog::record('deleted', "Menghapus sesi Stok Opname \"{$code}\"");

        return redirect()->route('stock-take.index')
            ->with('success', "Sesi Stok Opname {$code} berhasil dihapus.");
    }
}
