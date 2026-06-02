@extends('layouts.app')

@section('title', 'Data Ruangan')

@section('content')
<!-- Content Header -->
<div class="flex justify-between items-start mb-6" data-purpose="page-title-section">
<div>
<h1 class="text-3xl font-bold text-slate-900">Data Ruangan</h1>
<p class="text-sm text-slate-500 mt-1">Kelola daftar ruangan yang ada.</p>
</div>
@if(auth()->user()->role === 'Superadmin')
<button class="bg-[#3B82F6] hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium flex items-center shadow-sm transition-all">
<i class="w-4 h-4 mr-2" data-lucide="plus"></i>
          Tambah Ruangan
        </button>
@endif
</div>
<!-- BEGIN: Data Table Container -->
<section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" data-purpose="table-container">
<!-- Table Toolbar -->
<div class="p-4 border-b border-slate-100 flex items-center justify-between" data-purpose="table-toolbar">
<div class="relative w-72">
<span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
<i class="w-4 h-4" data-lucide="search"></i>
</span>
<input class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari kode atau nama ruangan..." type="text"/>
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
<table class="w-full text-left border-collapse whitespace-nowrap" id="ruangan-table">
<thead class="bg-slate-50/50">
<tr>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">No</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kode Ruangan</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Ruangan</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Penanggung Jawab</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Keterangan</th>
@if(auth()->user()->role === 'Superadmin')
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
@endif
</tr>
</thead>
<tbody class="divide-y divide-slate-100">
<!-- Row 1 -->
<tr class="table-row-hover transition-colors">
<td class="px-6 py-4 text-sm text-slate-600">1</td>
<td class="px-6 py-4 text-sm font-medium text-slate-900">R-SERVER-01</td>
<td class="px-6 py-4 text-sm text-slate-600">Ruang Server Utama</td>
<td class="px-6 py-4 text-sm text-slate-600">Ahmad Hasan</td>
<td class="px-6 py-4 text-sm text-slate-600">Gedung B Lantai 2</td>
@if(auth()->user()->role === 'Superadmin')
<td class="px-6 py-4 text-center">
<div class="flex justify-center space-x-3">
<button class="text-slate-500 hover:text-slate-700"><i class="w-4 h-4" data-lucide="pencil"></i></button>
<button class="text-red-400 hover:text-red-600"><i class="w-4 h-4" data-lucide="trash-2"></i></button>
</div>
</td>
@endif
</tr>
<!-- Row 2 -->
<tr class="bg-slate-50/30 table-row-hover transition-colors">
<td class="px-6 py-4 text-sm text-slate-600">2</td>
<td class="px-6 py-4 text-sm font-medium text-slate-900">R-NOC-01</td>
<td class="px-6 py-4 text-sm text-slate-600">Network Operation Center</td>
<td class="px-6 py-4 text-sm text-slate-600">Budi Santoso</td>
<td class="px-6 py-4 text-sm text-slate-600">Gedung B Lantai 2</td>
@if(auth()->user()->role === 'Superadmin')
<td class="px-6 py-4 text-center">
<div class="flex justify-center space-x-3">
<button class="text-slate-500 hover:text-slate-700"><i class="w-4 h-4" data-lucide="pencil"></i></button>
<button class="text-red-400 hover:text-red-600"><i class="w-4 h-4" data-lucide="trash-2"></i></button>
</div>
</td>
@endif
</tr>
<!-- Row 3 -->
<tr class="table-row-hover transition-colors">
<td class="px-6 py-4 text-sm text-slate-600">3</td>
<td class="px-6 py-4 text-sm font-medium text-slate-900">R-GUDANG-01</td>
<td class="px-6 py-4 text-sm text-slate-600">Gudang IT Hardware</td>
<td class="px-6 py-4 text-sm text-slate-600">Citra Lestari</td>
<td class="px-6 py-4 text-sm text-slate-600">Gedung C Lantai 1</td>
@if(auth()->user()->role === 'Superadmin')
<td class="px-6 py-4 text-center">
<div class="flex justify-center space-x-3">
<button class="text-slate-500 hover:text-slate-700"><i class="w-4 h-4" data-lucide="pencil"></i></button>
<button class="text-red-400 hover:text-red-600"><i class="w-4 h-4" data-lucide="trash-2"></i></button>
</div>
</td>
@endif
</tr>
<!-- Row 4 -->
<tr class="bg-slate-50/30 table-row-hover transition-colors">
<td class="px-6 py-4 text-sm text-slate-600">4</td>
<td class="px-6 py-4 text-sm font-medium text-slate-900">R-LAB-RPL1</td>
<td class="px-6 py-4 text-sm text-slate-600">Lab Komputer RPL 1</td>
<td class="px-6 py-4 text-sm text-slate-600">Dian Permana</td>
<td class="px-6 py-4 text-sm text-slate-600">Gedung A Lantai 3</td>
@if(auth()->user()->role === 'Superadmin')
<td class="px-6 py-4 text-center">
<div class="flex justify-center space-x-3">
<button class="text-slate-500 hover:text-slate-700"><i class="w-4 h-4" data-lucide="pencil"></i></button>
<button class="text-red-400 hover:text-red-600"><i class="w-4 h-4" data-lucide="trash-2"></i></button>
</div>
</td>
@endif
</tr>
<!-- Row 5 -->
<tr class="table-row-hover transition-colors">
<td class="px-6 py-4 text-sm text-slate-600">5</td>
<td class="px-6 py-4 text-sm font-medium text-slate-900">R-LAB-TKJ1</td>
<td class="px-6 py-4 text-sm text-slate-600">Lab Komputer TKJ 1</td>
<td class="px-6 py-4 text-sm text-slate-600">Eko Wahyudi</td>
<td class="px-6 py-4 text-sm text-slate-600">Gedung A Lantai 2</td>
@if(auth()->user()->role === 'Superadmin')
<td class="px-6 py-4 text-center">
<div class="flex justify-center space-x-3">
<button class="text-slate-500 hover:text-slate-700"><i class="w-4 h-4" data-lucide="pencil"></i></button>
<button class="text-red-400 hover:text-red-600"><i class="w-4 h-4" data-lucide="trash-2"></i></button>
</div>
</td>
@endif
</tr>
</tbody>
</table>
</div>
<!-- Table Pagination -->
<div class="px-6 py-4 flex items-center justify-between border-t border-slate-100" data-purpose="table-pagination">
<p class="text-xs text-slate-500">Menampilkan 1-5 dari 5 data</p>
<div class="flex items-center space-x-2">
<button class="p-2 text-slate-400 hover:text-slate-600 disabled:opacity-30" disabled="">
<i class="w-4 h-4" data-lucide="chevron-left"></i>
</button>
<button class="w-8 h-8 flex items-center justify-center bg-slate-100 text-slate-900 rounded text-xs font-bold">1</button>
<button class="p-2 text-slate-400 hover:text-slate-600 disabled:opacity-30" disabled="">
<i class="w-4 h-4" data-lucide="chevron-right"></i>
</button>
</div>
</div>
</section>
<!-- END: Table Container Card -->

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script data-purpose="lucide-icon-init">
    // Initialize icons
    lucide.createIcons();
</script>
@endpush
@endsection