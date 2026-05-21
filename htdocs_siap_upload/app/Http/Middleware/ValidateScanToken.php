<?php

namespace App\Http\Middleware;

use App\Models\ScanSession;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateScanToken
{
    /**
     * Validasi bahwa request memiliki token QR yang valid.
     * Blokir akses langsung tanpa token atau dengan token yang expired/used.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->route('token');

        if (!$token) {
            abort(403, 'Akses ditolak. Token QR tidak ditemukan.');
        }

        $session = ScanSession::where('token', $token)->first();

        if (!$session) {
            abort(403, 'Token QR tidak valid.');
        }

        if (!$session->isValid()) {
            return response()->view('qr.expired', [], 403);
        }

        // Simpan session ke request agar bisa diakses di controller
        $request->merge(['scan_session' => $session]);

        return $next($request);
    }
}
