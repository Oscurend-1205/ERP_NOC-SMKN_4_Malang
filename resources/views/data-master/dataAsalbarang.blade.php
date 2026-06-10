@extends('layouts.app')

@section('title', 'Asal Barang')

@section('content')
<!-- BEGIN: Page Title & Action -->
<div class="flex justify-between items-start mb-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Asal Barang</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola data asal barang.</p>
    </div>
    @if(auth()->user()->role === 'Superadmin')
    <button onclick="document.getElementById('addAsalModal').classList.remove('hidden')" class="bg-[#3B82F6] hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium flex items-center shadow-sm transition-all">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Asal Barang
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
            <input type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari data asal barang...">
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
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Asal Barang</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Keterangan</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Status</th>
                    @if(auth()->user()->role === 'Superadmin')
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($asals as $asal)
                <tr class="{{ $loop->even ? 'bg-slate-50/30' : '' }} table-row-hover transition-colors">
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $loop->iteration + $asals->firstItem() - 1 }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $asal->name }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $asal->description ?? '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($asal->is_active)
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
                            <button onclick="openEditAsalModal({{ $asal->id }}, '{{ addslashes($asal->name) }}', '{{ addslashes($asal->description ?? '') }}')" class="text-slate-500 hover:text-slate-700"><i data-lucide="pencil" class="w-4 h-4"></i></button>
                            <form action="{{ route('asal.destroy', $asal->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data asal barang ini?');" class="inline">
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
                            <p class="text-sm font-medium">Belum ada data asal barang</p>
                            <p class="text-xs mt-1 text-slate-400">Data asal barang yang ditambahkan akan muncul di sini</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Table Pagination -->
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $asals->links() }}
    </div>
</section>
<!-- END: Data Table Section -->

<!-- Modal Tambah Asal Barang -->
<div id="addAsalModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('addAsalModal').classList.add('hidden')"></div>
    
    <div class="relative w-full max-w-[450px] bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] font-sans">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
            <h2 class="text-lg font-bold text-slate-900">Tambah Asal Barang</h2>
            <button type="button" onclick="document.getElementById('addAsalModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('asal.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="px-6 py-5 space-y-4 overflow-y-auto">
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Nama Asal Barang</label>
                    <input type="text" name="name" required placeholder="Contoh: BOS, BPOPP, dll" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Keterangan</label>
                    <textarea name="description" rows="3" placeholder="Deskripsi asal barang" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400"></textarea>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 mt-auto">
                <button type="button" onclick="document.getElementById('addAsalModal').classList.add('hidden')" class="px-5 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
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
<!-- Modal Edit Asal Barang -->
<div id="editAsalModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('editAsalModal').classList.add('hidden')"></div>
    
    <div class="relative w-full max-w-[450px] bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] font-sans">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
            <h2 class="text-lg font-bold text-slate-900">Edit Asal Barang</h2>
            <button type="button" onclick="document.getElementById('editAsalModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="editAsalForm" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            @method('PUT')
            <div class="px-6 py-5 space-y-4 overflow-y-auto">
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Nama Asal Barang</label>
                    <input type="text" id="edit_asal_name" name="name" required placeholder="Contoh: BOS, BPOPP, dll" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Keterangan</label>
                    <textarea id="edit_asal_description" name="description" rows="3" placeholder="Deskripsi asal barang" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400"></textarea>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 mt-auto">
                <button type="button" onclick="document.getElementById('editAsalModal').classList.add('hidden')" class="px-5 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
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
    function openEditAsalModal(id, name, description) {
        document.getElementById('editAsalForm').action = `/asal-barang/${id}`;
        document.getElementById('edit_asal_name').value = name;
        document.getElementById('edit_asal_description').value = description;
        document.getElementById('editAsalModal').classList.remove('hidden');
    }
</script>
@endpush