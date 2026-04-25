<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemMovement;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemMovementController extends Controller
{
    /**
     * Tampilkan riwayat pergerakan barang.
     */
    public function index(Request $request)
    {
        $query = ItemMovement::with(['item', 'user', 'fromLocation', 'toLocation']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('item_id')) {
            $query->where('item_id', $request->item_id);
        }

        $movements = $query->orderBy('movement_date', 'desc')->paginate(15);
        $items = Item::all();

        return view('movements.index', compact('movements', 'items'));
    }

    /**
     * Tampilkan form tambah pergerakan.
     */
    public function create()
    {
        $items = Item::with('location')->get();
        $locations = Location::all();
        return view('movements.create', compact('items', 'locations'));
    }

    /**
     * Simpan pergerakan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'type' => 'required|in:masuk,keluar,pindah,maintenance,rusak,musnahkan',
            'from_location_id' => 'nullable|exists:locations,id',
            'to_location_id' => 'nullable|exists:locations,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'movement_date' => 'required|date',
        ]);

        $validated['user_id'] = Auth::id();

        $movement = ItemMovement::create($validated);
        $item = Item::find($validated['item_id']);

        // Logika Update Stok dan Atribut Barang
        switch ($validated['type']) {
            case 'masuk':
                $item->increment('quantity', $validated['quantity']);
                break;
            case 'keluar':
                if ($item->quantity >= $validated['quantity']) {
                    $item->decrement('quantity', $validated['quantity']);
                }
                break;
            case 'pindah':
                if ($validated['to_location_id']) {
                    $item->update(['location_id' => $validated['to_location_id']]);
                }
                break;
            case 'maintenance':
                $item->update(['status' => 'maintenance']);
                break;
            case 'rusak':
                $item->update(['condition' => 'rusak_berat']);
                break;
            case 'musnahkan':
                if ($item->quantity >= $validated['quantity']) {
                    $item->decrement('quantity', $validated['quantity']);
                }
                $item->update(['status' => 'dimusnahkan']);
                break;
        }

        return redirect()->route('movements.index')
            ->with('success', 'Pergerakan barang berhasil dicatat.');
    }

    /**
     * Simpan pinjaman barang (dari modal Dashboard).
     */
    public function storeLoan(Request $request)
    {
        $validated = $request->validate([
            'borrower_name' => 'required|string|max:255',
            'borrower_id' => 'required|string|max:255',
            'borrower_phone' => 'required|string|max:20',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'movement_date' => 'required|date',
        ]);

        $notes = "Peminjam: {$validated['borrower_name']} (ID: {$validated['borrower_id']}), HP: {$validated['borrower_phone']}";

        $movement = ItemMovement::create([
            'item_id' => $validated['item_id'],
            'user_id' => Auth::id(),
            'type' => 'keluar',
            'quantity' => $validated['quantity'],
            'movement_date' => $validated['movement_date'],
            'notes' => $notes,
        ]);

        $item = Item::find($validated['item_id']);
        if ($item->quantity >= $validated['quantity']) {
            $item->decrement('quantity', $validated['quantity']);
        }
        
        // Update item status to dipinjam
        $item->update(['status' => 'dipinjam']);

        return redirect()->back()->with('success', 'Pinjaman barang berhasil dicatat.');
    }
}
