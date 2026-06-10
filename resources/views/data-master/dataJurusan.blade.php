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
    <button onclick="document.getElementById('addJurusanModal').classList.remove('hidden')" class="bg-[#3B82F6] hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium flex items-center shadow-sm transition-all">
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
<button onclick="openEditJurusanModal({{ $jurusan->id }}, '{{ addslashes($jurusan->name) }}', '{{ addslashes($jurusan->description ?? '') }}', {{ $jurusan->is_active ? '1' : '0' }})" class="text-slate-500 hover:text-slate-700"><i class="w-4 h-4" data-lucide="pencil"></i></button>
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

<!-- Modal Tambah Jurusan -->
<div id="addJurusanModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('addJurusanModal').classList.add('hidden')"></div>
    
    <div class="relative w-full max-w-[450px] bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] font-sans">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
            <h2 class="text-lg font-bold text-slate-900">Tambah Jurusan</h2>
            <button type="button" onclick="document.getElementById('addJurusanModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('jurusan.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="px-6 py-5 space-y-4 overflow-y-auto">
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Nama Jurusan</label>
                    <input type="text" name="name" required placeholder="Masukkan nama jurusan" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Keterangan</label>
                    <textarea name="description" rows="3" placeholder="Penjelasan atau detail jurusan" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400"></textarea>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Status</label>
                    <select name="is_active" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 mt-auto">
                <button type="button" onclick="document.getElementById('addJurusanModal').classList.add('hidden')" class="px-5 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
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
<!-- Modal Edit Jurusan -->
<div id="editJurusanModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('editJurusanModal').classList.add('hidden')"></div>
    
    <div class="relative w-full max-w-[450px] bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] font-sans">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
            <h2 class="text-lg font-bold text-slate-900">Edit Jurusan</h2>
            <button type="button" onclick="document.getElementById('editJurusanModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="editJurusanForm" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            @method('PUT')
            <div class="px-6 py-5 space-y-4 overflow-y-auto">
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Nama Jurusan</label>
                    <input type="text" id="edit_jurusan_name" name="name" required placeholder="Masukkan nama jurusan" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Keterangan</label>
                    <textarea id="edit_jurusan_description" name="description" rows="3" placeholder="Penjelasan atau detail jurusan" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400"></textarea>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Status</label>
                    <select id="edit_jurusan_is_active" name="is_active" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 mt-auto">
                <button type="button" onclick="document.getElementById('editJurusanModal').classList.add('hidden')" class="px-5 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
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
    function openEditJurusanModal(id, name, description, isActive) {
        document.getElementById('editJurusanForm').action = `/data-jurusan/${id}`;
        document.getElementById('edit_jurusan_name').value = name;
        document.getElementById('edit_jurusan_description').value = description;
        document.getElementById('edit_jurusan_is_active').value = isActive;
        document.getElementById('editJurusanModal').classList.remove('hidden');
    }
</script>
@endpush