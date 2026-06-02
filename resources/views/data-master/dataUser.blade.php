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
<button class="bg-[#3B82F6] hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium flex items-center shadow-sm transition-all">
<i class="w-4 h-4 mr-2" data-lucide="plus"></i>
        Tambah User Baru
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
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Username</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Role/Jabatan</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
@if(auth()->user()->role === 'Superadmin')
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
@endif
</tr>
</thead>
<tbody class="divide-y divide-slate-100">
<!-- Row 1 -->
<tr class="table-row-hover transition-colors">
<td class="px-6 py-4 text-sm text-slate-600">1</td>
<td class="px-6 py-4 text-sm font-medium text-slate-900">
<div class="flex items-center gap-3">
<div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs">AH</div>
<span class="font-bold text-gray-900">Ahmad Hasan</span>
<span class="bg-gray-100 text-gray-500 text-[9px] px-1.5 py-0.5 rounded ml-2 border border-gray-200">Anda</span>
</div>
</td>
<td class="px-6 py-4 text-sm text-slate-600">admin_ahmad</td>
<td class="px-6 py-4 text-sm text-slate-600">ahmad@noc.smkn4.sch.id</td>
<td class="px-6 py-4 text-sm font-medium text-blue-700">Superadmin</td>
<td class="px-6 py-4">
<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100">
<span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span>
                  Aktif
                </span>
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
<!-- Row 2 -->
<tr class="bg-slate-50/30 table-row-hover transition-colors">
<td class="px-6 py-4 text-sm text-slate-600">2</td>
<td class="px-6 py-4 text-sm font-medium text-slate-900">
<div class="flex items-center gap-3">
<div class="w-9 h-9 rounded-full bg-slate-400 text-white flex items-center justify-center font-bold text-xs">BS</div>
<span class="font-bold text-gray-900">Budi Santoso</span>
</div>
</td>
<td class="px-6 py-4 text-sm text-slate-600">budi_staff</td>
<td class="px-6 py-4 text-sm text-slate-600">budi.s@noc.smkn4.sch.id</td>
<td class="px-6 py-4 text-sm text-slate-600">Admin</td>
<td class="px-6 py-4">
<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100">
<span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span>
                  Aktif
                </span>
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
<!-- Row 3 -->
<tr class="table-row-hover transition-colors">
<td class="px-6 py-4 text-sm text-slate-600">3</td>
<td class="px-6 py-4 text-sm font-medium text-slate-900">
<div class="flex items-center gap-3">
<div class="w-9 h-9 rounded-full bg-slate-300 text-gray-600 flex items-center justify-center font-bold text-xs">CD</div>
<span class="font-bold text-gray-900">Citra Dewi</span>
</div>
</td>
<td class="px-6 py-4 text-sm text-slate-600">citra_d</td>
<td class="px-6 py-4 text-sm text-slate-600">citra@noc.smkn4.sch.id</td>
<td class="px-6 py-4 text-sm text-slate-600">Admin</td>
<td class="px-6 py-4">
<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600 border border-red-100">
<span class="w-2 h-2 bg-red-500 rounded-full mr-1.5"></span>
                  Non-Aktif
                </span>
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
<!-- Row 4 -->
<tr class="bg-slate-50/30 table-row-hover transition-colors">
<td class="px-6 py-4 text-sm text-slate-600">4</td>
<td class="px-6 py-4 text-sm font-medium text-slate-900">
<div class="flex items-center gap-3">
<div class="w-9 h-9 rounded-full bg-blue-500 text-white flex items-center justify-center font-bold text-xs">DW</div>
<span class="font-bold text-gray-900">Dian Wibowo</span>
</div>
</td>
<td class="px-6 py-4 text-sm text-slate-600">dian_sa</td>
<td class="px-6 py-4 text-sm text-slate-600">dian.w@noc.smkn4.sch.id</td>
<td class="px-6 py-4 text-sm font-medium text-blue-700">Superadmin</td>
<td class="px-6 py-4">
<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100">
<span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span>
                  Aktif
                </span>
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
</tbody>
</table>
</div>
<!-- Table Pagination -->
<div class="px-6 py-4 flex items-center justify-between border-t border-slate-100" data-purpose="table-pagination">
<p class="text-xs text-slate-500">Menampilkan 1-4 dari 4 data</p>
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
<!-- END: Data Table Section -->

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script data-purpose="icon-initialization">
    /* Initialize Lucide icons after DOM content is loaded */
    document.addEventListener('DOMContentLoaded', () => {
      lucide.createIcons();
    });
</script>
@endpush
@endsection