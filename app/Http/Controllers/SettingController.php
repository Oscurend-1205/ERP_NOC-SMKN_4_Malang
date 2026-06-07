<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        return view('settings.index');
    }

    /**
     * Reset the system (Fresh migrate and seed).
     */
    public function resetSystem(Request $request)
    {
        // Pastikan hanya Superadmin yang bisa mengakses ini
        if (Auth::user()->role !== 'Superadmin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk tindakan ini.');
        }

        try {
            // Jalankan migrate:fresh --seed
            Artisan::call('migrate:fresh', [
                '--seed' => true,
                '--force' => true, // Force the operation in production
            ]);

            // Logout setelah reset karena semua user (termasuk session yang aktif) sudah terhapus dan dibuat ulang
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('success', 'Sistem berhasil direset ke pengaturan awal (Data default). Silakan login kembali.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mereset sistem: ' . $e->getMessage());
        }
    }
}
