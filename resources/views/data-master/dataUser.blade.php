@extends('layouts.app')

@section('title', 'Data User')

@section('content')
<!-- BEGIN: Page Title & Action -->
<div class="flex justify-between items-start mb-6" data-purpose="page-title-section">
<div>
<h1 class="text-3xl font-bold text-slate-900">Data User</h1>
<p class="text-sm text-slate-500 mt-1">Kelola data user.</p>
</div>
@if(auth()->user()->role === 'Superadmin')
    <button onclick="document.getElementById('addUserModal').classList.remove('hidden')" class="bg-[#3B82F6] hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium flex items-center shadow-sm transition-all">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah User Baru
    </button>
@endif
</div>
<!-- END: Page Title & Action -->
<!-- BEGIN: Data Table Section -->
<section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" data-purpose="table-container">
<!-- Table Toolbar -->
<div class="p-4 border-b border-slate-100 flex items-center justify-between" data-purpose="table-toolbar">
<div class="relative w-72">
<span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
<i class="w-4 h-4" data-lucide="search"></i>
</span>
<input class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari username atau nama..." type="text"/>
</div>
<div class="flex items-center space-x-3">
<button class="flex items-center px-4 py-2 border border-slate-200 rounded-lg text-sm text-slate-600 hover:bg-slate-50">
<i class="w-4 h-4 mr-2" data-lucide="filter"></i>
              Filter
            </button>
<button class="flex items-center px-4 py-2 border border-slate-200 rounded-lg text-sm text-slate-600 hover:bg-slate-50">
<i class="w-4 h-4 mr-2" data-lucide="download"></i>
              Export
            </button>
</div>
</div>
<!-- The Table -->
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse whitespace-nowrap" id="user-table">
<thead class="bg-slate-50/50">
<tr>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">No</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Lengkap</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">NISN/NUPTK</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kelas</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Jabatan</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
@if(auth()->user()->role === 'Superadmin')
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
@endif
</tr>
</thead>
<tbody class="divide-y divide-slate-100">
@forelse($users as $index => $user)
<tr class="{{ $loop->iteration % 2 === 0 ? 'bg-slate-50/30' : '' }} table-row-hover transition-colors">
<td class="px-6 py-4 text-sm text-slate-600">{{ $users->firstItem() + $index }}</td>
<td class="px-6 py-4 text-sm font-medium text-slate-900">
<div class="flex items-center gap-3">
@php
    $initials = collect(explode(' ', $user->name))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
    $colors = ['bg-blue-600', 'bg-slate-400', 'bg-indigo-500', 'bg-emerald-500', 'bg-amber-500', 'bg-rose-500'];
    $color = $colors[strlen($user->name) % count($colors)];
@endphp
<div class="w-9 h-9 rounded-full {{ $color }} text-white flex items-center justify-center font-bold text-xs uppercase">{{ $initials }}</div>
<span class="font-bold text-gray-900">{{ $user->name }}</span>
@if(auth()->id() === $user->id)
<span class="bg-gray-100 text-gray-500 text-[9px] px-1.5 py-0.5 rounded ml-2 border border-gray-200">Anda</span>
@endif
</div>
</td>
<td class="px-6 py-4 text-sm text-slate-600">{{ $user->username ?? '-' }}</td>
<td class="px-6 py-4 text-sm text-slate-600">{{ $user->email ?? '-' }}</td>
<td class="px-6 py-4 text-sm font-medium text-blue-700">{{ $user->role }}</td>
<td class="px-6 py-4">
@if($user->is_active)
<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100">
<span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span>
  Aktif
</span>
@else
<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600 border border-red-100">
<span class="w-2 h-2 bg-red-500 rounded-full mr-1.5"></span>
  Non-Aktif
</span>
@endif
</td>
@if(auth()->user()->role === 'Superadmin')
<td class="px-6 py-4 text-center">
<div class="flex justify-center space-x-3">
<button class="text-slate-500 hover:text-slate-700"><i class="w-4 h-4" data-lucide="pencil"></i></button>
<button class="text-red-400 hover:text-red-600"><i class="w-4 h-4" data-lucide="trash-2"></i></button>
</div>
</td>
@endif
</tr>
@empty
<tr>
<td colspan="7" class="px-6 py-8 text-center text-slate-500">Belum ada data user.</td>
</tr>
@endforelse
</tbody>
</table>
</div>
<!-- Table Pagination -->
<div class="px-6 py-4 border-t border-slate-100" data-purpose="table-pagination">
    {{ $users->links() }}
</div>
</section>
<!-- END: Data Table Section -->

<!-- Modal Tambah User -->
<div id="addUserModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <!-- Backdrop Blur -->
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('addUserModal').classList.add('hidden')"></div>
    
    <!-- Modal Content -->
    <div class="relative w-full max-w-[450px] bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] font-sans">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
            <h2 class="text-lg font-bold text-slate-900">Tambah User Baru</h2>
            <button onclick="document.getElementById('addUserModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Form Body -->
        <form action="{{ route('users.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="px-6 py-5 space-y-4 overflow-y-auto">
                
                <!-- Nama Lengkap -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Masukkan nama lengkap" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>

                <!-- NISN/NUPTK -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">NISN/NUPTK</label>
                    <input type="text" name="username" required placeholder="Masukkan NISN atau NUPTK" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>

                <!-- Kelas -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Kelas</label>
                    <input type="text" name="email" required placeholder="Contoh: XII RPL 1" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>

                <!-- Jabatan -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Jabatan</label>
                    <select name="role" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-700 bg-white">
                        <option value="" disabled selected hidden>Pilih Jabatan</option>
                        <option value="Superadmin">Superadmin</option>
                        <option value="Admin">Admin</option>
                        <option value="Siswa">Siswa</option>
                        <option value="Guru">Guru</option>
                    </select>
                </div>

            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 mt-auto">
                <button type="button" onclick="document.getElementById('addUserModal').classList.add('hidden')" class="px-5 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    Simpan User
                </button>
            </div>
        </form>
    </div>
</div>

@endsection