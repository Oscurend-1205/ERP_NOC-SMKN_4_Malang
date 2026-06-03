@extends('layouts.app')

@section('title', 'Data Supplier')

@section('content')
<!-- BEGIN: Page Title & Action -->
<div class="flex justify-between items-start mb-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Data Supplier</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola data supplier untuk NOC SMKN 4 Malang.</p>
    </div>
    @if(auth()->user()->role === 'Superadmin')
    <button class="bg-[#3B82F6] hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium flex items-center shadow-sm transition-all">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Supplier
    </button>
    @endif
</div>
<!-- END: Page Title & Action -->

<!-- BEGIN: Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6">
    <!-- Total Supplier -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex items-center">
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mr-4 flex-shrink-0">
            <i data-lucide="factory" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Supplier</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">124</p>
        </div>
    </div>
    
    <!-- Supplier Aktif -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex items-center">
        <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center mr-4 flex-shrink-0">
            <i data-lucide="check-circle" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Supplier Aktif</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">118</p>
        </div>
    </div>
    
    <!-- Pengiriman -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex items-center">
        <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center mr-4 flex-shrink-0">
            <i data-lucide="truck" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-tight mb-0.5">Pengiriman Bulan Ini</p>
            <p class="text-2xl font-bold text-slate-800">42</p>
        </div>
    </div>
    
    <!-- Update Terakhir -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex items-center">
        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mr-4 flex-shrink-0">
            <i data-lucide="clock" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Update Terakhir</p>
            <p class="text-sm font-bold text-slate-800 mt-1">2 Jam Lalu</p>
        </div>
    </div>
</div>
<!-- END: Statistics Cards -->

<!-- BEGIN: Data Table Section -->
<section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <!-- Table Toolbar -->
    <div class="p-4 border-b border-slate-100 flex items-center justify-between">
        <div class="relative w-72">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                <i class="w-4 h-4" data-lucide="search"></i>
            </span>
            <input type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari nama supplier atau PIC...">
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
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Supplier</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">PIC</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">No. Telepon/WA</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Alamat</th>
                    @if(auth()->user()->role === 'Superadmin')
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <!-- Row 1 -->
                <tr class="table-row-hover transition-colors">
                    <td class="px-6 py-4 text-sm text-slate-600">1</td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">PT. Media Teknologi Nusantara</td>
                    <td class="px-6 py-4 text-sm text-slate-600">Budi Setiawan</td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        <div class="flex items-center gap-2">
                            <i data-lucide="message-square" class="w-3.5 h-3.5 text-green-500"></i> +62 812-3456-7890
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">sales@mediatech.id</td>
                    <td class="px-6 py-4 text-sm text-slate-600 truncate max-w-[200px]">Jl. Sudirman No. 45, Jakarta Pusat</td>
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
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">Indo Computer Solution</td>
                    <td class="px-6 py-4 text-sm text-slate-600">Siti Aminah</td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        <div class="flex items-center gap-2">
                            <i data-lucide="message-square" class="w-3.5 h-3.5 text-green-500"></i> +62 856-7890-1234
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">support@indecomp.co.id</td>
                    <td class="px-6 py-4 text-sm text-slate-600 truncate max-w-[200px]">Ruko Grand Palace Kav. 12, Malang</td>
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
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">Global Network Hardware</td>
                    <td class="px-6 py-4 text-sm text-slate-600">Rendi Wijaya</td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        <div class="flex items-center gap-2">
                            <i data-lucide="message-square" class="w-3.5 h-3.5 text-green-500"></i> +62 811-2233-4455
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">admin@globalnet.com</td>
                    <td class="px-6 py-4 text-sm text-slate-600 truncate max-w-[200px]">Kawasan industri Pulogadung Block C, Jakarta</td>
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
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">Malang Fiber Optic</td>
                    <td class="px-6 py-4 text-sm text-slate-600">Dewi Lestari</td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        <div class="flex items-center gap-2">
                            <i data-lucide="message-square" class="w-3.5 h-3.5 text-green-500"></i> +62 878-1122-3344
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">contact@malangfiber.net</td>
                    <td class="px-6 py-4 text-sm text-slate-600 truncate max-w-[200px]">Jl. Ijen No. 100, Malang</td>
                    @if(auth()->user()->role === 'Superadmin')
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center space-x-3">
                            <button class="text-slate-500 hover:text-slate-700"><i data-lucide="pencil" class="w-4 h-4"></i></button>
                            <button class="text-red-400 hover:text-red-600"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                        </div>
                    </td>
                    @endif
                </tr>
                <!-- Row 5 -->
                <tr class="table-row-hover transition-colors">
                    <td class="px-6 py-4 text-sm text-slate-600">5</td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">E-Katalog Tech Store</td>
                    <td class="px-6 py-4 text-sm text-slate-600">Andi Prasetyo</td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        <div class="flex items-center gap-2">
                            <i data-lucide="message-square" class="w-3.5 h-3.5 text-green-500"></i> +62 813-5566-7788
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">sales@ekatalogtech.com</td>
                    <td class="px-6 py-4 text-sm text-slate-600 truncate max-w-[200px]">Jl. Gajah Mada No. 8, Surabaya</td>
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
        <p class="text-xs text-slate-500">Menampilkan 1-5 dari 24 data</p>
        <div class="flex items-center space-x-2">
            <button class="p-2 text-slate-400 hover:text-slate-600 disabled:opacity-30" disabled>
                <i class="w-4 h-4" data-lucide="chevron-left"></i>
            </button>
            <button class="w-8 h-8 flex items-center justify-center bg-slate-100 text-slate-900 rounded text-xs font-bold">1</button>
            <button class="w-8 h-8 flex items-center justify-center hover:bg-slate-50 text-slate-600 rounded text-xs">2</button>
            <button class="w-8 h-8 flex items-center justify-center hover:bg-slate-50 text-slate-600 rounded text-xs">3</button>
            <button class="p-2 text-slate-600 hover:text-slate-800">
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