@extends('layouts.app')

@section('title', 'Kategori Barang')

@section('content')
<!-- BEGIN: Page Title & Action -->
<div class="flex justify-between items-start mb-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Kategori Barang</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola data kategori barang.</p>
    </div>
    @if(auth()->user()->role === 'Superadmin')
    <button class="bg-[#3B82F6] hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium flex items-center shadow-sm transition-all">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Kategori
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
            <input type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari kategori barang...">
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
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Kategori</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Keterangan</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Jumlah Barang</th>
                    @if(auth()->user()->role === 'Superadmin')
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($categories as $category)
                <tr class="{{ $loop->even ? 'bg-slate-50/30' : '' }} table-row-hover transition-colors">
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $loop->iteration + $categories->firstItem() - 1 }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $category->name }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $category->description ?? '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                            {{ $category->items_count }} Barang
                        </span>
                    </td>
                    @if(auth()->user()->role === 'Superadmin')
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center space-x-3">
                            <button class="text-slate-500 hover:text-slate-700"><i data-lucide="pencil" class="w-4 h-4"></i></button>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 border-none bg-transparent cursor-pointer"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
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
                            <p class="text-sm font-medium">Belum ada data kategori</p>
                            <p class="text-xs mt-1 text-slate-400">Kategori yang ditambahkan akan muncul di sini</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Table Pagination -->
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $categories->links() }}
    </div>
</section>
<!-- END: Data Table Section -->

@endsection