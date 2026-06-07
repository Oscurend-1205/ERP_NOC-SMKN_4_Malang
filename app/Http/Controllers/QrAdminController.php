<?php

namespace App\Http\Controllers;

use App\Models\ScanSession;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QrAdminController extends Controller
{
    /**
     * Tampilkan halaman panel QR admin.
     */
    public function index()
    {
        $activeSessions = ScanSession::where('created_by', Auth::id())
            ->where('expired_at', '>', now())
            ->where('is_used', false)
            ->latest()
            ->get();

        $recentPeminjaman = Peminjaman::with('item')
            ->latest()
            ->limit(20)
            ->get();

        $availableItems = \App\Models\Item::where('status', 'tersedia')->orderBy('name')->get();
        $users = \App\Models\User::select('name', 'role')->orderBy('name')->get();
        $jurusans = \App\Models\Jurusan::select('name')->orderBy('name')->get();

        return view('qr.admin-panel', compact('activeSessions', 'recentPeminjaman', 'availableItems', 'users', 'jurusans'));
    }

    /**
     * Generate QR Code baru (token session).
     */
    public function generateQr(Request $request)
    {
        $minutes = $request->input('expiry_minutes', 10);
        $session = ScanSession::generateToken(Auth::id(), $minutes);
        
        // Gunakan fungsi route() agar URL yang dihasilkan sesuai dengan domain/IP yang sedang diakses
        $scanUrl = route('qr.scan', ['token' => $session->token]);

        return response()->json([
            'success' => true,
            'token' => $session->token,
            'scan_url' => $scanUrl,
            'expired_at' => $session->expired_at->format('H:i:s'),
            'expired_at_full' => $session->expired_at->toIso8601String(),
        ]);
    }

    /**
     * Polling: ambil data peminjaman terbaru untuk real-time update.
     */
    public function pollPeminjaman(Request $request)
    {
        $since = $request->input('since');

        $query = Peminjaman::with('item')->latest();

        if ($since) {
            $query->where('created_at', '>', $since);
        }

        $data = $query->limit(20)->get()->map(function ($p) {
            return [
                'id' => $p->id_pinjam,
                'nama_peminjam' => $p->nama_peminjam,
                'kelas' => $p->kelas,
                'item_name' => $p->item->name ?? '-',
                'item_code' => $p->item_code,
                'waktu_pinjam' => $p->waktu_pinjam->format('d/m/Y H:i'),
                'status' => $p->status,
                'created_at' => $p->created_at->toIso8601String(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'server_time' => now()->toIso8601String(),
        ]);
    }

    /**
     * Batalkan / nonaktifkan token QR.
     */
    public function revokeToken(Request $request, string $token)
    {
        $session = ScanSession::where('token', $token)
            ->where('created_by', Auth::id())
            ->first();

        if ($session) {
            $session->update(['is_used' => true]);
        }

        return response()->json(['success' => true]);
    }
}
