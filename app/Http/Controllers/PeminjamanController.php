<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        if (auth()->user()->isJurusan()) {
            $jurusanName = auth()->user()->jurusan->name ?? '';
            $query->where('kelas', $jurusanName);
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

    public function returnItem(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status === 'dipinjam') {
            $request->validate([
                'kondisi_saat_kembali' => 'nullable|string|in:baik,rusak_ringan,rusak_berat,hilang',
                'keterangan_kembali' => 'nullable|string|max:1000',
                'foto_kembali' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $kondisi = $request->input('kondisi_saat_kembali');
            $keterangan = $request->input('keterangan_kembali');
            $fotoPath = null;

            // Handle photo upload
            if ($request->hasFile('foto_kembali')) {
                $fotoPath = $request->file('foto_kembali')->store('pengembalian', 'public');
            }

            $updateData = [
                'status' => 'dikembalikan',
                'waktu_kembali' => now(),
                'kondisi_saat_kembali' => $kondisi,
                'keterangan_kembali' => $keterangan,
            ];

            if ($fotoPath) {
                $updateData['foto_kembali'] = $fotoPath;
            }

            $peminjaman->update($updateData);

            // Kembalikan stok barang dan update kondisi
            $item = $peminjaman->item;
            if ($item) {
                $item->increment('quantity', 1);
                if ($item->status === 'dipinjam') {
                    $item->update(['status' => 'tersedia']);
                }
                // Update kondisi barang berdasarkan kondisi saat dikembalikan
                if ($kondisi) {
                    $item->update(['condition' => $kondisi]);
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
