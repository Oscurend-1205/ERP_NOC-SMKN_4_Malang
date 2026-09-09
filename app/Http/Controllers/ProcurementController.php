<?php

namespace App\Http\Controllers;

use App\Models\Procurement;
use App\Models\ProcurementItem;
use App\Models\Category;
use App\Models\Jurusan;
use App\Models\Location;
use App\Models\Item;
use App\Models\ItemMovement;
use App\Models\Notification;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProcurementController extends Controller
{
    /**
     * Tampilkan daftar pengajuan pengadaan alat.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Procurement::accessibleBy($user)
            ->with(['user', 'jurusan', 'approver', 'items'])
            ->latest();

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter prioritas
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter jurusan
        if ($request->filled('jurusan_id')) {
            $query->where('jurusan_id', $request->jurusan_id);
        }

        // Search text
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('justification', 'like', "%{$search}%");
            });
        }

        // Hitung statistik KPI berdasarkan role user
        $baseStats = Procurement::accessibleBy($user);
        $kpi = [
            'total' => (clone $baseStats)->count(),
            'pending' => (clone $baseStats)->where('status', 'pending')->count(),
            'approved' => (clone $baseStats)->whereIn('status', ['approved', 'in_procurement'])->count(),
            'completed' => (clone $baseStats)->where('status', 'completed')->count(),
            'total_cost' => (clone $baseStats)->whereIn('status', ['approved', 'in_procurement', 'completed'])->sum('total_estimated_cost'),
            'pending_cost' => (clone $baseStats)->where('status', 'pending')->sum('total_estimated_cost'),
        ];

        $procurements = $query->paginate(12)->withQueryString();
        $jurusans = Jurusan::orderBy('name')->get();

        return view('procurements.index', compact('procurements', 'kpi', 'jurusans'));
    }

    /**
     * Tampilkan form pembuatan pengajuan pengadaan baru.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $jurusans = Jurusan::orderBy('name')->get();

        return view('procurements.create', compact('categories', 'jurusans'));
    }

    /**
     * Simpan pengajuan pengadaan baru beserta rincian item.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'priority' => 'required|in:rendah,sedang,tinggi,mendesak',
            'target_date' => 'nullable|date',
            'jurusan_id' => 'nullable|exists:jurusans,id',
            'justification' => 'nullable|string',
            'notes' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx,xls|max:5120',
            'action_type' => 'required|in:draft,submit',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.category_id' => 'nullable|exists:categories,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit' => 'required|string|max:50',
            'items.*.estimated_unit_price' => 'required|numeric|min:0',
            'items.*.specification' => 'nullable|string',
            'items.*.reference_url' => 'nullable|url|max:500',
        ], [
            'title.required' => 'Judul pengadaan wajib diisi.',
            'items.required' => 'Minimal masukkan 1 barang yang ingin diajukan.',
            'items.*.item_name.required' => 'Nama barang pada setiap baris wajib diisi.',
            'items.*.quantity.min' => 'Jumlah barang minimal 1.',
            'items.*.estimated_unit_price.numeric' => 'Estimasi harga satuan harus berupa angka.',
            'attachment.max' => 'Ukuran file lampiran maksimal 5MB.',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $status = ($request->action_type === 'submit') ? 'pending' : 'draft';

            // Upload lampiran jika ada
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('procurements', 'public');
            }

            // Tentukan jurusan_id jika user adalah Jurusan
            $jurusanId = $request->jurusan_id;
            if ($user->isJurusan() && empty($jurusanId)) {
                $jurusanId = $user->jurusan_id;
            }

            $procurement = Procurement::create([
                'title' => $request->title,
                'user_id' => $user->id,
                'jurusan_id' => $jurusanId,
                'priority' => $request->priority,
                'target_date' => $request->target_date,
                'status' => $status,
                'justification' => $request->justification,
                'notes' => $request->notes,
                'attachment' => $attachmentPath,
                'total_estimated_cost' => 0,
            ]);

            // Simpan detail item
            foreach ($request->items as $itemData) {
                ProcurementItem::create([
                    'procurement_id' => $procurement->id,
                    'category_id' => $itemData['category_id'] ?? null,
                    'item_name' => $itemData['item_name'],
                    'specification' => $itemData['specification'] ?? null,
                    'quantity' => $itemData['quantity'],
                    'unit' => $itemData['unit'] ?? 'Unit',
                    'estimated_unit_price' => $itemData['estimated_unit_price'],
                    'reference_url' => $itemData['reference_url'] ?? null,
                    'notes' => $itemData['notes'] ?? null,
                ]);
            }

            // Hitung ulang total biaya
            $procurement->recalculateTotal();

            // Notifikasi ke Superadmin & Admin jika langsung disubmit
            if ($status === 'pending') {
                Notification::sendToAdmins(
                    type: 'info',
                    title: 'Pengajuan Pengadaan Baru',
                    message: "Pengajuan {$procurement->code} ({$procurement->title}) diajukan oleh {$user->name}.",
                    actionUrl: route('procurements.show', $procurement->id),
                    icon: 'shopping_cart'
                );
            }

            // Catat log aktivitas
            ActivityLog::record(
                'created',
                "Membuat usulan pengadaan \"{$procurement->code}\" - {$procurement->title} (Status: {$procurement->status_label})",
                $procurement
            );

            DB::commit();

            $pesan = ($status === 'pending')
                ? "Pengajuan pengadaan {$procurement->code} berhasil dikirim dan menunggu persetujuan."
                : "Draf pengajuan pengadaan {$procurement->code} berhasil disimpan.";

            return redirect()->route('procurements.show', $procurement->id)->with('success', $pesan);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan pengajuan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail pengajuan pengadaan alat.
     */
    public function show($id)
    {
        $procurement = Procurement::with(['user', 'jurusan', 'approver', 'items.category'])->findOrFail($id);

        // Validasi hak akses
        $user = Auth::user();
        if ($user->isJurusan() && $procurement->user_id !== $user->id && $procurement->jurusan_id !== $user->jurusan_id) {
            abort(403, 'Anda tidak memiliki akses ke pengajuan ini.');
        }

        $categories = Category::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        return view('procurements.show', compact('procurement', 'categories', 'locations'));
    }

    /**
     * Form edit pengajuan pengadaan (hanya untuk status draft atau rejected).
     */
    public function edit($id)
    {
        $procurement = Procurement::with(['items'])->findOrFail($id);
        $user = Auth::user();

        // Validasi edit
        if (!$user->isSuperadmin() && !in_array($procurement->status, ['draft', 'rejected'])) {
            return redirect()->route('procurements.show', $procurement->id)
                ->with('error', 'Pengajuan dengan status ini tidak dapat diubah.');
        }

        if ($user->isJurusan() && $procurement->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki wewenang untuk mengedit pengajuan ini.');
        }

        $categories = Category::orderBy('name')->get();
        $jurusans = Jurusan::orderBy('name')->get();

        return view('procurements.edit', compact('procurement', 'categories', 'jurusans'));
    }

    /**
     * Update pengajuan pengadaan.
     */
    public function update(Request $request, $id)
    {
        $procurement = Procurement::findOrFail($id);
        $user = Auth::user();

        if (!$user->isSuperadmin() && !in_array($procurement->status, ['draft', 'rejected'])) {
            return redirect()->route('procurements.show', $procurement->id)
                ->with('error', 'Pengajuan dengan status ini tidak dapat diubah.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'priority' => 'required|in:rendah,sedang,tinggi,mendesak',
            'target_date' => 'nullable|date',
            'jurusan_id' => 'nullable|exists:jurusans,id',
            'justification' => 'nullable|string',
            'notes' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx,xls|max:5120',
            'action_type' => 'required|in:draft,submit',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.category_id' => 'nullable|exists:categories,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit' => 'required|string|max:50',
            'items.*.estimated_unit_price' => 'required|numeric|min:0',
            'items.*.specification' => 'nullable|string',
            'items.*.reference_url' => 'nullable|url|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Upload file baru jika ada
            if ($request->hasFile('attachment')) {
                if ($procurement->attachment && Storage::disk('public')->exists($procurement->attachment)) {
                    Storage::disk('public')->delete($procurement->attachment);
                }
                $procurement->attachment = $request->file('attachment')->store('procurements', 'public');
            }

            $newStatus = ($request->action_type === 'submit') ? 'pending' : $procurement->status;

            $procurement->update([
                'title' => $request->title,
                'jurusan_id' => $request->jurusan_id ?: $procurement->jurusan_id,
                'priority' => $request->priority,
                'target_date' => $request->target_date,
                'status' => $newStatus,
                'justification' => $request->justification,
                'notes' => $request->notes,
            ]);

            // Hapus items lama dan ganti dengan yang baru
            $procurement->items()->delete();

            foreach ($request->items as $itemData) {
                ProcurementItem::create([
                    'procurement_id' => $procurement->id,
                    'category_id' => $itemData['category_id'] ?? null,
                    'item_name' => $itemData['item_name'],
                    'specification' => $itemData['specification'] ?? null,
                    'quantity' => $itemData['quantity'],
                    'unit' => $itemData['unit'] ?? 'Unit',
                    'estimated_unit_price' => $itemData['estimated_unit_price'],
                    'reference_url' => $itemData['reference_url'] ?? null,
                    'notes' => $itemData['notes'] ?? null,
                ]);
            }

            $procurement->recalculateTotal();

            // Notifikasi ke admin jika diajukan
            if ($newStatus === 'pending') {
                Notification::sendToAdmins(
                    type: 'info',
                    title: 'Pengajuan Pengadaan Diperbarui',
                    message: "Pengajuan {$procurement->code} telah diperbarui dan diajukan kembali oleh {$user->name}.",
                    actionUrl: route('procurements.show', $procurement->id),
                    icon: 'shopping_cart'
                );
            }

            ActivityLog::record(
                'updated',
                "Memperbarui usulan pengadaan \"{$procurement->code}\"",
                $procurement
            );

            DB::commit();

            return redirect()->route('procurements.show', $procurement->id)
                ->with('success', "Pengajuan {$procurement->code} berhasil diperbarui.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui pengajuan: ' . $e->getMessage());
        }
    }

    /**
     * Submit draft pengajuan menjadi pending.
     */
    public function submit($id)
    {
        $procurement = Procurement::findOrFail($id);
        $user = Auth::user();

        if ($procurement->status !== 'draft' && $procurement->status !== 'rejected') {
            return back()->with('error', 'Hanya pengajuan draf atau ditolak yang dapat diajukan kembali.');
        }

        $procurement->update(['status' => 'pending']);

        // Kirim notifikasi
        Notification::sendToAdmins(
            type: 'info',
            title: 'Pengajuan Pengadaan Baru',
            message: "Pengajuan {$procurement->code} ({$procurement->title}) diajukan oleh {$user->name}.",
            actionUrl: route('procurements.show', $procurement->id),
            icon: 'shopping_cart'
        );

        ActivityLog::record('updated', "Mengajukan permohonan pengadaan {$procurement->code} untuk verifikasi", $procurement);

        return redirect()->route('procurements.show', $procurement->id)
            ->with('success', "Pengajuan {$procurement->code} berhasil diajukan untuk proses verifikasi.");
    }

    /**
     * Superadmin menyetujui pengajuan.
     */
    public function approve(Request $request, $id)
    {
        $procurement = Procurement::findOrFail($id);

        if (!Auth::user()->isSuperadmin()) {
            abort(403, 'Hanya Superadmin yang berwenang menyetujui pengajuan.');
        }

        $request->validate([
            'approval_notes' => 'nullable|string|max:1000',
        ]);

        $procurement->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'notes' => $request->approval_notes ? ($procurement->notes . "\n[Catatan Persetujuan]: " . $request->approval_notes) : $procurement->notes,
            'rejection_reason' => null,
        ]);

        // Tandai status item menjadi approved
        $procurement->items()->update(['status' => 'approved']);

        // Kirim notifikasi ke pemohon
        Notification::send(
            userId: $procurement->user_id,
            type: 'success',
            title: 'Pengajuan Pengadaan Disetujui',
            message: "Pengajuan Anda \"{$procurement->title}\" ({$procurement->code}) telah DISETUJUI oleh " . Auth::user()->name . ".",
            actionUrl: route('procurements.show', $procurement->id),
            icon: 'check_circle'
        );

        ActivityLog::record('approved', "Menyetujui usulan pengadaan {$procurement->code} senilai {$procurement->formatted_total_cost}", $procurement);

        return redirect()->route('procurements.show', $procurement->id)
            ->with('success', "Pengajuan {$procurement->code} berhasil disetujui.");
    }

    /**
     * Superadmin menolak pengajuan.
     */
    public function reject(Request $request, $id)
    {
        $procurement = Procurement::findOrFail($id);

        if (!Auth::user()->isSuperadmin()) {
            abort(403, 'Hanya Superadmin yang berwenang menolak pengajuan.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi agar pemohon dapat memperbaiki usulan.',
        ]);

        $procurement->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        $procurement->items()->update(['status' => 'rejected']);

        // Kirim notifikasi ke pemohon
        Notification::send(
            userId: $procurement->user_id,
            type: 'danger',
            title: 'Pengajuan Pengadaan Ditolak',
            message: "Pengajuan {$procurement->code} ditolak. Alasan: \"{$request->rejection_reason}\"",
            actionUrl: route('procurements.show', $procurement->id),
            icon: 'cancel'
        );

        ActivityLog::record('rejected', "Menolak usulan pengadaan {$procurement->code}. Alasan: {$request->rejection_reason}", $procurement);

        return redirect()->route('procurements.show', $procurement->id)
            ->with('success', "Pengajuan {$procurement->code} berhasil ditolak.");
    }

    /**
     * Update status pengadaan (in_procurement atau completed).
     */
    public function updateStatus(Request $request, $id)
    {
        $procurement = Procurement::findOrFail($id);

        if (!Auth::user()->isSuperadmin() && !Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'status' => 'required|in:in_procurement,completed',
            'notes' => 'nullable|string',
        ]);

        $data = ['status' => $request->status];
        if ($request->status === 'completed') {
            $data['completed_at'] = now();
        }
        if ($request->filled('notes')) {
            $data['notes'] = $procurement->notes . "\n[Update Status]: " . $request->notes;
        }

        $procurement->update($data);

        // Notifikasi ke pemohon
        $statusText = ($request->status === 'in_procurement') ? 'Sedang Diproses Pengadaan' : 'Selesai / Terealisasi';
        Notification::send(
            userId: $procurement->user_id,
            type: 'info',
            title: 'Update Pengadaan: ' . $statusText,
            message: "Pengajuan {$procurement->code} kini berstatus {$statusText}.",
            actionUrl: route('procurements.show', $procurement->id),
            icon: ($request->status === 'completed') ? 'task_alt' : 'local_shipping'
        );

        ActivityLog::record('updated', "Mengubah status pengadaan {$procurement->code} menjadi {$statusText}", $procurement);

        return redirect()->route('procurements.show', $procurement->id)
            ->with('success', "Status pengadaan {$procurement->code} berhasil diubah menjadi {$statusText}.");
    }

    /**
     * Penerimaan barang dari pengadaan & opsi konversi langsung ke inventaris (Data Barang).
     */
    public function receiveItem(Request $request, $id)
    {
        $procurement = Procurement::findOrFail($id);

        if (!Auth::user()->isSuperadmin() && !Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'procurement_item_id' => 'required|exists:procurement_items,id',
            'received_qty' => 'required|integer|min:1',
            'convert_to_inventory' => 'nullable|boolean',
            'location_id' => 'nullable|required_if:convert_to_inventory,1|exists:locations,id',
            'condition' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $item = ProcurementItem::where('procurement_id', $procurement->id)
                ->where('id', $request->procurement_item_id)
                ->firstOrFail();

            $newReceived = $item->received_quantity + $request->received_qty;
            $isFullyReceived = $newReceived >= $item->quantity;

            $item->update([
                'received_quantity' => $newReceived,
                'status' => $isFullyReceived ? 'received' : 'partially_received',
            ]);

            // Jika dicentang opsi masukkan ke Inventaris Barang
            if ($request->boolean('convert_to_inventory')) {
                // Generate item code jika kategori ada prefix
                $category = $item->category;
                $prefix = $category ? ($category->prefix ?? 'BRG') : 'BRG';
                $nextNum = Item::where('category_id', $item->category_id)->count() + 1;
                $generatedCode = $prefix . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

                $newItem = Item::create([
                    'name' => $item->item_name,
                    'code' => $generatedCode,
                    'category_id' => $item->category_id,
                    'location_id' => $request->location_id,
                    'quantity' => $request->received_qty,
                    'condition' => strtolower($request->condition ?? 'baik'),
                    'status' => 'tersedia',
                    'purchase_date' => now(),
                    'purchase_price' => $item->estimated_unit_price,
                    'notes' => "Pengadaan {$procurement->code}: {$item->specification}",
                ]);

                // Catat pergerakan barang masuk
                ItemMovement::create([
                    'item_id' => $newItem->id,
                    'user_id' => Auth::id(),
                    'type' => 'masuk',
                    'jenis_barang_masuk' => 'Pengadaan',
                    'to_location_id' => $request->location_id,
                    'quantity' => $request->received_qty,
                    'notes' => "Penerimaan pengadaan dari {$procurement->code}",
                    'movement_date' => now(),
                ]);
            }

            // Periksa jika seluruh item dalam procurement sudah diterima
            $allReceived = $procurement->items()->where('status', '!=', 'received')->count() === 0;
            if ($allReceived) {
                $procurement->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);
            } else if ($procurement->status === 'approved') {
                $procurement->update(['status' => 'in_procurement']);
            }

            ActivityLog::record(
                'updated',
                "Menerima {$request->received_qty} {$item->unit} barang \"{$item->item_name}\" dari pengadaan {$procurement->code}",
                $procurement
            );

            DB::commit();

            return redirect()->route('procurements.show', $procurement->id)
                ->with('success', "Barang \"{$item->item_name}\" berhasil diterima (" . ($request->boolean('convert_to_inventory') ? 'dan otomatis dicatat ke Data Inventaris' : 'tercatat') . ").");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mencatat penerimaan barang: ' . $e->getMessage());
        }
    }

    /**
     * Hapus pengajuan pengadaan (hanya draf atau ditolak).
     */
    public function destroy($id)
    {
        $procurement = Procurement::findOrFail($id);
        $user = Auth::user();

        if (!$user->isSuperadmin() && !in_array($procurement->status, ['draft', 'rejected'])) {
            return back()->with('error', 'Hanya pengajuan draf atau ditolak yang dapat dihapus.');
        }

        if ($user->isJurusan() && $procurement->user_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        // Hapus file attachment jika ada
        if ($procurement->attachment && Storage::disk('public')->exists($procurement->attachment)) {
            Storage::disk('public')->delete($procurement->attachment);
        }

        $code = $procurement->code;
        $procurement->delete();

        ActivityLog::record('deleted', "Menghapus pengajuan pengadaan {$code}", null);

        return redirect()->route('procurements.index')
            ->with('success', "Pengajuan pengadaan {$code} berhasil dihapus.");
    }

    /**
     * Tampilan cetak Surat Permohonan Pengadaan Barang resmi (A4 Print-Ready).
     */
    public function print($id)
    {
        $procurement = Procurement::with(['user', 'jurusan', 'approver', 'items.category'])->findOrFail($id);

        $user = Auth::user();
        if ($user->isJurusan() && $procurement->user_id !== $user->id && $procurement->jurusan_id !== $user->jurusan_id) {
            abort(403, 'Akses ditolak.');
        }

        return view('procurements.print', compact('procurement'));
    }
}
