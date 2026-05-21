<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Peminjaman;
use App\Models\ScanSession;
use Illuminate\Http\Request;

class QrScanController extends Controller
{
    /**
     * Tampilkan halaman scanner setelah validasi token.
     */
    public function showScanner(Request $request, string $token)
    {
        $session = $request->scan_session;

        return view('qr.scanner', [
            'token' => $token,
            'expired_at' => $session->expired_at->toIso8601String(),
        ]);
    }

    /**
     * API: Lookup data barang berdasarkan kode QR.
     */
    public function lookupItem(Request $request, string $token, string $code)
    {
        // Validasi token masih aktif
        $session = ScanSession::where('token', $token)->first();
        if (!$session || !$session->isValid()) {
            return response()->json(['success' => false, 'message' => 'Sesi QR telah berakhir.'], 403);
        }

        // Cari barang berdasarkan kode
        $item = Item::where('code', $code)->first();

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Barang dengan kode "' . $code . '" tidak ditemukan.',
            ], 404);
        }

        // Cek ketersediaan
        if ($item->status === 'dipinjam') {
            return response()->json([
                'success' => false,
                'message' => 'Barang "' . $item->name . '" sedang dipinjam.',
            ], 422);
        }

        if ($item->quantity <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Stok barang "' . $item->name . '" habis.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'item' => [
                'id' => $item->id,
                'name' => $item->name,
                'code' => $item->code,
                'brand' => $item->brand,
                'model' => $item->model,
                'condition' => $item->condition_label,
                'status' => $item->status_label,
                'quantity' => $item->quantity,
                'location' => $item->location->name ?? '-',
                'category' => $item->category->name ?? '-',
            ],
        ]);
    }

    /**
     * Submit peminjaman barang.
     */
    public function submitPeminjaman(Request $request, string $token)
    {
        // Validasi token
        $session = ScanSession::where('token', $token)->first();
        if (!$session || !$session->isValid()) {
            return response()->json(['success' => false, 'message' => 'Sesi QR telah berakhir.'], 403);
        }

        // Validasi input
        $validated = $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'item_id' => 'required|exists:items,id',
            'item_code' => 'required|string',
            'catatan' => 'nullable|string|max:500',
        ]);

        // Cek lagi ketersediaan barang
        $item = Item::find($validated['item_id']);
        if (!$item || $item->quantity <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak tersedia untuk dipinjam.',
            ], 422);
        }

        // Simpan peminjaman
        $peminjaman = Peminjaman::create([
            'nama_peminjam' => $validated['nama_peminjam'],
            'kelas' => $validated['kelas'],
            'item_id' => $validated['item_id'],
            'item_code' => $validated['item_code'],
            'session_token' => $token,
            'waktu_pinjam' => now(),
            'status' => 'dipinjam',
            'catatan' => $validated['catatan'] ?? null,
        ]);

        // Update stok barang
        $item->decrement('quantity', 1);
        if ($item->quantity <= 0) {
            $item->update(['status' => 'dipinjam']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman berhasil dicatat!',
            'data' => [
                'id' => $peminjaman->id,
                'nama' => $peminjaman->nama_peminjam,
                'kelas' => $peminjaman->kelas,
                'barang' => $item->name,
                'waktu' => $peminjaman->waktu_pinjam->format('d/m/Y H:i'),
            ],
        ]);
    }
}
