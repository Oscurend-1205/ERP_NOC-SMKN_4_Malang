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
        return view('data-master.dataUser', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|max:255', // Digunakan untuk menyimpan Kelas (format non-email)
            'role' => 'required|string|in:Superadmin,Admin,Siswa,Guru',
        ]);

        $validated['user_code'] = 'USR-' . (\App\Models\User::max('id') + 1);
        $validated['password'] = Hash::make('password123'); // Default password
        $validated['is_active'] = $request->has('is_active') ? true : true; // Aktif default jika dari form

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }
}
