<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Proses login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ]);
    }

    /**
     * Proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar.');
    }
    /**
     * Tampilkan halaman pengeset sandi.
     */
    public function showPasswordSetup()
    {
        return view('auth.password-setup');
    }

    /**
     * Update sandi untuk admin utama.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:8',
        ], [
            'email.exists' => 'Email admin tidak ditemukan.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
        ]);

        $user = User::where('email', $request->email)->first();
        
        if ($user) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return redirect()->route('login')->with('success', 'Kata sandi berhasil diperbarui. Silakan login.');
        }

        return back()->withErrors(['email' => 'Gagal memperbarui kata sandi.']);
    }
}
