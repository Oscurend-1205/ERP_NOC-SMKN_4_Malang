@extends('layouts.app')

@section('title', 'Data Master Jurusan')

@section('content')
<!-- Page Title and Actions -->
<div class="flex justify-between items-start mb-6" data-purpose="page-title-section">
<div>
<h1 class="text-3xl font-bold text-slate-900">Data Master Jurusan</h1>
<p class="text-sm text-slate-500 mt-1">Kelola daftar program keahlian dan jurusan sekolah.</p>
</div>
@if(auth()->user()->role === 'Superadmin')
    <button class="bg-[#3B82F6] hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium flex items-center shadow-sm transition-all">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Jurusan
    </button>
@endif
</div>
<!-- BEGIN: Table Container Card -->
<section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" data-purpose="table-container">
<!-- Table Toolbar -->
<div class="p-4 border-b border-slate-100 flex items-center justify-between" data-purpose="table-toolbar">
<div class="relative w-72">
<span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
<i class="w-4 h-4" data-lucide="search"></i>
</span>
<input class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari kode atau nama jurusan..." type="text"/>
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
<table class="w-full text-left border-collapse whitespace-nowrap" id="jurusan-table">
<thead class="bg-slate-50/50">
<tr>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">No</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kode / Nama Jurusan</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Keterangan</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Status</th>
@if(auth()->user()->role === 'Superadmin')
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
@endif
</tr>
</thead>
<tbody class="divide-y divide-slate-100">
@forelse($jurusans as $jurusan)
<tr class="{{ $loop->even ? 'bg-slate-50/30' : '' }} table-row-hover transition-colors">
<td class="px-6 py-4 text-sm text-slate-600">{{ $loop->iteration + $jurusans->firstItem() - 1 }}</td>
<td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $jurusan->name }}</td>
<td class="px-6 py-4 text-sm text-slate-600">{{ $jurusan->description ?? '-' }}</td>
<td class="px-6 py-4 text-center">
@if($jurusan->is_active)
    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100">
        <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span> Aktif
    </span>
@else
    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-50 text-slate-600 border border-slate-200">
        <span class="w-2 h-2 bg-slate-400 rounded-full mr-1.5"></span> Nonaktif
    </span>
@endif
</td>
@if(auth()->user()->role === 'Superadmin')
<td class="px-6 py-4 text-center">
<div class="flex justify-center space-x-3">
<button class="text-slate-500 hover:text-slate-700"><i class="w-4 h-4" data-lucide="pencil"></i></button>
<form action="{{ route('jurusan.destroy', $jurusan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jurusan ini?');" class="inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="text-red-400 hover:text-red-600 border-none bg-transparent cursor-pointer"><i class="w-4 h-4" data-lucide="trash-2"></i></button>
</form>
</div>
</td>
@endif
</tr>
@empty
<tr>
    <td colspan="{{ auth()->user()->role === 'Superadmin' ? 5 : 4 }}" class="px-6 py-10 text-center text-slate-500">
        <div class="flex flex-col items-center justify-center">
            <i data-lucide="inbox" class="w-10 h-10 text-slate-300 mb-3"></i>
            <p class="text-sm font-medium">Belum ada data jurusan</p>
            <p class="text-xs mt-1 text-slate-400">Data jurusan yang ditambahkan akan muncul di sini</p>
        </div>
    </td>
</tr>
@endforelse
</tbody>
</table>
</div>
<!-- Table Pagination -->
<div class="px-6 py-4 border-t border-slate-100" data-purpose="table-pagination">
    {{ $jurusans->links() }}
</div>
</section>
<!-- END: Table Container Card -->

@endsection