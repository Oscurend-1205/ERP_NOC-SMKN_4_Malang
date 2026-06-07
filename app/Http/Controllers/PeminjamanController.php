<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with('item');

        if ($request->filled('jurusan') && $request->jurusan !== 'Semua Jurusan') {
            $query->where('kelas', 'like', '%' . $request->jurusan . '%');
        }

        if ($request->filled('start_date')) {
            $query->whereDate('waktu_pinjam', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('waktu_pinjam', '<=', $request->end_date);
        }

        $peminjamans = $query->orderBy('waktu_pinjam', 'desc')->paginate(15);
        $totalDipinjam = Peminjaman::where('status', 'dipinjam')->count();

        return view('data-pengguna.dataPeminjam', compact('peminjamans', 'totalDipinjam'));
    }

    public function destroy(Peminjaman $peminjaman)
    {
        $peminjaman->delete();
        return redirect()->back()->with('success', 'Data peminjaman berhasil dihapus.');
    }

    public function returnItem(Peminjaman $peminjaman)
    {
        if ($peminjaman->status === 'dipinjam') {
            $peminjaman->update([
                'status' => 'dikembalikan',
                'waktu_kembali' => now(),
            ]);

            // Kembalikan stok barang
            $item = $peminjaman->item;
            if ($item) {
                $item->increment('quantity', 1);
                if ($item->status === 'dipinjam') {
                    $item->update(['status' => 'tersedia']);
                }
            }

            return redirect()->back()->with('success', 'Barang berhasil dikembalikan.');
        }

        return redirect()->back()->with('error', 'Status peminjaman tidak valid.');
    }

    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'borrower_name' => 'required|string|max:255|exists:users,name',
            'kelas' => 'required|string|max:255|exists:jurusans,name',
            'borrower_phone' => 'nullable|string|max:20',
            'item_id' => 'required|exists:items,id',
            'item_code' => 'required|string|max:255|exists:items,code',
            'movement_date' => 'required|date',
        ]);

        $item = \App\Models\Item::find($validated['item_id']);
        if (!$item || $item->quantity < 1) {
            return redirect()->back()->with('error', 'Stok barang tidak mencukupi untuk dipinjam.');
        }

        $catatan = "HP: " . ($validated['borrower_phone'] ?? '-');

        // Peminjaman diinputkan satu persatu
        Peminjaman::create([
            'nama_peminjam' => $validated['borrower_name'],
            'kelas' => $validated['kelas'],
            'item_id' => $validated['item_id'],
            'item_code' => $validated['item_code'], // Menggunakan ID Barang spesifik
            'session_token' => 'MANUAL-' . \Illuminate\Support\Str::random(10), // Fix constraint violation
            'waktu_pinjam' => $validated['movement_date'] . ' ' . now()->format('H:i:s'),
            'status' => 'dipinjam',
            'catatan' => $catatan,
        ]);

        // Update item quantity
        $item->decrement('quantity', 1);
        if ($item->quantity <= 0) {
            $item->update(['status' => 'dipinjam']);
        }

        return redirect()->back()->with('success', 'Pinjaman barang berhasil dicatat ke sistem!');
    }
}
