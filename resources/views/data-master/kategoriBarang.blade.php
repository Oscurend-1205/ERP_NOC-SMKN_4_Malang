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
    <button onclick="document.getElementById('addKategoriModal').classList.remove('hidden')" class="bg-[#3B82F6] hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium flex items-center shadow-sm transition-all">
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
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Prefix Kode</th>
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
                    <td class="px-6 py-4 text-center">
                        <code class="text-xs bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-md font-mono font-bold border border-indigo-100">{{ $category->prefix ?? '-' }}</code>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $category->description ?? '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                            {{ $category->items_count }} Barang
                        </span>
                    </td>
                    @if(auth()->user()->role === 'Superadmin')
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center space-x-3">
                            <button onclick="openEditKategoriModal({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->description ?? '') }}', '{{ addslashes($category->prefix ?? '') }}')" class="text-slate-500 hover:text-slate-700"><i data-lucide="pencil" class="w-4 h-4"></i></button>
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
                    <td colspan="{{ auth()->user()->role === 'Superadmin' ? 6 : 5 }}" class="px-6 py-10 text-center text-slate-500">
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

<!-- Modal Tambah Kategori -->
<div id="addKategoriModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('addKategoriModal').classList.add('hidden')"></div>
    
    <div class="relative w-full max-w-[450px] bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] font-sans">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
            <h2 class="text-lg font-bold text-slate-900">Tambah Kategori</h2>
            <button type="button" onclick="document.getElementById('addKategoriModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('categories.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="px-6 py-5 space-y-4 overflow-y-auto">
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Nama Kategori</label>
                    <input type="text" name="name" required placeholder="Masukkan nama kategori" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Prefix Kode <span class="text-red-500">*</span></label>
                    <input type="text" name="prefix" required maxlength="10" placeholder="Contoh: PRF, SBN, KSM" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400 uppercase font-mono font-bold" style="text-transform:uppercase">
                    <p class="text-[11px] text-slate-400">Kode awalan untuk nomor inventaris barang di kategori ini (maks 10 karakter)</p>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Keterangan</label>
                    <textarea name="description" rows="3" placeholder="Deskripsi kategori" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400"></textarea>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 mt-auto">
                <button type="button" onclick="document.getElementById('addKategoriModal').classList.add('hidden')" class="px-5 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<!-- Modal Edit Kategori -->
<div id="editKategoriModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('editKategoriModal').classList.add('hidden')"></div>

    <div class="relative w-full max-w-[450px] bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] font-sans">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
            <h2 class="text-lg font-bold text-slate-900">Edit Kategori</h2>   
            <button type="button" onclick="document.getElementById('editKategoriModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="editKategoriForm" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            @method('PUT')
            <div class="px-6 py-5 space-y-4 overflow-y-auto">
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Nama Kategori</label>
                    <input type="text" id="edit_kategori_name" name="name" required placeholder="Masukkan nama kategori" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Prefix Kode <span class="text-red-500">*</span></label>
                    <input type="text" id="edit_kategori_prefix" name="prefix" required maxlength="10" placeholder="Contoh: PRF, SBN, KSM" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400 uppercase font-mono font-bold" style="text-transform:uppercase">
                    <p class="text-[11px] text-slate-400">Kode awalan untuk nomor inventaris barang di kategori ini (maks 10 karakter)</p>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Keterangan</label>
                    <textarea id="edit_kategori_description" name="description" rows="3" placeholder="Deskripsi kategori" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400"></textarea>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 mt-auto">
                <button type="button" onclick="document.getElementById('editKategoriModal').classList.add('hidden')" class="px-5 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">  
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditKategoriModal(id, name, description, prefix) {
        document.getElementById('editKategoriForm').action = `/kategori-barang/${id}`;
        document.getElementById('edit_kategori_name').value = name;
        document.getElementById('edit_kategori_description').value = description;
        document.getElementById('edit_kategori_prefix').value = prefix;
        document.getElementById('editKategoriModal').classList.remove('hidden');
    }
</script>
@endpush