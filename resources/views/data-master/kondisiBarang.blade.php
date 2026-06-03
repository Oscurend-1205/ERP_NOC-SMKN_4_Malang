@extends('layouts.app')

@section('title', 'Kondisi Barang')

@section('content')
<!-- BEGIN: Page Title & Action -->
<div class="flex justify-between items-start mb-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Kondisi Barang</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola data kondisi barang.</p>
    </div>
    @if(auth()->user()->role === 'Superadmin')
    <button class="bg-[#3B82F6] hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium flex items-center shadow-sm transition-all">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Kondisi
    </button>
    @endif
</div>
<!-- END: Page Title & Action -->

<!-- BEGIN: Data Table Section -->
<section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <!-- Table Toolbar -->
    <div class="p-4 border-b border-slate-100 flex items-center justify-between">
        <div class="relative w-72">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                <i class="w-4 h-4" data-lucide="search"></i>
            </span>
            <input type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari kondisi barang...">
        </div>
        <div class="flex items-center space-x-3">
            <button class="flex items-center px-4 py-2 border border-slate-200 rounded-lg text-sm text-slate-600 hover:bg-slate-50">
                <i data-lucide="filter" class="w-4 h-4 mr-2"></i> Filter
            </button>
            <button class="flex items-center px-4 py-2 border border-slate-200 rounded-lg text-sm text-slate-600 hover:bg-slate-50">
                <i data-lucide="download" class="w-4 h-4 mr-2"></i> Export
            </button>
        </div>
    </div>

    <!-- The Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead class="bg-slate-50/50">
                <tr>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Kondisi</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Preview Label</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Keterangan</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Status</th>
                    @if(auth()->user()->role === 'Superadmin')
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <!-- Row 1 -->
                <tr class="table-row-hover transition-colors">
                    <td class="px-6 py-4 text-sm text-slate-600">1</td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">Baik</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100">
                            Baik
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">Barang dapat berfungsi 100% normal tanpa cacat.</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span> Aktif
                        </span>
                    </td>
                    @if(auth()->user()->role === 'Superadmin')
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center space-x-3">
                            <button class="text-slate-500 hover:text-slate-700"><i data-lucide="pencil" class="w-4 h-4"></i></button>
                            <button class="text-red-400 hover:text-red-600"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                        </div>
                    </td>
                    @endif
                </tr>
                <!-- Row 2 -->
                <tr class="bg-slate-50/30 table-row-hover transition-colors">
                    <td class="px-6 py-4 text-sm text-slate-600">2</td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">Kurang Baik</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-100">
                            Kurang Baik
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">Berfungsi namun ada minor cacat fisik atau performa menurun.</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span> Aktif
                        </span>
                    </td>
                    @if(auth()->user()->role === 'Superadmin')
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center space-x-3">
                            <button class="text-slate-500 hover:text-slate-700"><i data-lucide="pencil" class="w-4 h-4"></i></button>
                            <button class="text-red-400 hover:text-red-600"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                        </div>
                    </td>
                    @endif
                </tr>
                <!-- Row 3 -->
                <tr class="table-row-hover transition-colors">
                    <td class="px-6 py-4 text-sm text-slate-600">3</td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">Rusak Berat</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600 border border-red-100">
                            Rusak Berat
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">Barang tidak dapat digunakan sama sekali dan perlu dihapus (afkir).</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span> Aktif
                        </span>
                    </td>
                    @if(auth()->user()->role === 'Superadmin')
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center space-x-3">
                            <button class="text-slate-500 hover:text-slate-700"><i data-lucide="pencil" class="w-4 h-4"></i></button>
                            <button class="text-red-400 hover:text-red-600"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                        </div>
                    </td>
                    @endif
                </tr>
                <!-- Row 4 -->
                <tr class="bg-slate-50/30 table-row-hover transition-colors">
                    <td class="px-6 py-4 text-sm text-slate-600">4</td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">Dalam Perbaikan</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                            Dalam Perbaikan
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">Sedang dalam proses service atau garansi vendor.</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600 border border-red-100">
                            <span class="w-2 h-2 bg-red-500 rounded-full mr-1.5"></span> Non-Aktif
                        </span>
                    </td>
                    @if(auth()->user()->role === 'Superadmin')
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center space-x-3">
                            <button class="text-slate-500 hover:text-slate-700"><i data-lucide="pencil" class="w-4 h-4"></i></button>
                            <button class="text-red-400 hover:text-red-600"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                        </div>
                    </td>
                    @endif
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Table Pagination -->
    <div class="px-6 py-4 flex items-center justify-between border-t border-slate-100">
        <p class="text-xs text-slate-500">Menampilkan 1-4 dari 4 data</p>
        <div class="flex items-center space-x-2">
            <button class="p-2 text-slate-400 hover:text-slate-600 disabled:opacity-30" disabled>
                <i class="w-4 h-4" data-lucide="chevron-left"></i>
            </button>
            <button class="w-8 h-8 flex items-center justify-center bg-slate-100 text-slate-900 rounded text-xs font-bold">1</button>
            <button class="p-2 text-slate-400 hover:text-slate-600 disabled:opacity-30" disabled>
                <i class="w-4 h-4" data-lucide="chevron-right"></i>
            </button>
        </div>
    </div>
</section>
<!-- END: Data Table Section -->

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
@endpush
@endsection