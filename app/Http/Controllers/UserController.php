<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        $totalUsers = User::count();
        $jurusans = \App\Models\Jurusan::where('is_active', true)->get();
        return view('data-master.dataUser', compact('users', 'totalUsers', 'jurusans'));
    }

    public function profile()
    {
        return view('userProfile');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        $user->update($validated);

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required'     => 'Password baru wajib diisi.',
            'new_password.min'          => 'Password baru minimal 8 karakter.',
            'new_password.confirmed'    => 'Konfirmasi password tidak cocok.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('profile.index')->with('error', 'Password saat ini salah.');
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('profile.index')->with('success', 'Password berhasil diubah.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|max:255',
            'role' => 'required|string|in:Superadmin,Admin,Jurusan,Siswa,Guru',
        ]);

        $validated['user_code'] = 'USR-' . (\App\Models\User::max('id') + 1);
        $validated['password'] = Hash::make('password123');
        $validated['is_active'] = $request->has('is_active');

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        if (in_array($user->username, ['superadmin', 'admin'])) {
            return redirect()->route('users.index')->with('error', 'Akun ini adalah paten dan tidak dapat diedit.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|max:255',
            'role' => 'required|string|in:Superadmin,Admin,Jurusan,Siswa,Guru',
            'password' => 'nullable|string|min:6',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if (in_array($user->username, ['superadmin', 'admin'])) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Akun ini adalah paten dan tidak dapat dihapus.'], 403);
            }
            return redirect()->route('users.index')->with('error', 'Akun ini adalah paten dan tidak dapat dihapus.');
        }

        if ($user->id === auth()->id()) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Anda tidak dapat menghapus akun Anda sendiri.'], 403);
            }
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'User berhasil dihapus.']);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}

