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
    <button onclick="document.getElementById('addRuanganModal').classList.remove('hidden')" class="bg-[#3B82F6] hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium flex items-center shadow-sm transition-all">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Ruangan
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
@forelse($locations as $index => $location)
<tr class="{{ $loop->iteration % 2 === 0 ? 'bg-slate-50/30' : '' }} table-row-hover transition-colors">
<td class="px-6 py-4 text-sm text-slate-600">{{ $locations->firstItem() + $index }}</td>
<td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $location->code }}</td>
<td class="px-6 py-4 text-sm text-slate-600">{{ $location->name }}</td>
<td class="px-6 py-4 text-sm text-slate-600">{{ $location->penanggung_jawab ?? '-' }}</td>
<td class="px-6 py-4 text-sm text-slate-600">{{ $location->description ?? '-' }}</td>
@if(auth()->user()->role === 'Superadmin')
<td class="px-6 py-4 text-center">
<div class="flex justify-center space-x-3">
<button onclick="openEditRuanganModal({{ $location->id }}, '{{ $location->code }}', '{{ addslashes($location->name) }}', '{{ addslashes($location->penanggung_jawab ?? '') }}', '{{ addslashes($location->description ?? '') }}')" class="text-slate-500 hover:text-slate-700"><i class="w-4 h-4" data-lucide="pencil"></i></button>
<form action="{{ route('locations.destroy', $location->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus ruangan ini?');" class="inline">
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
<td colspan="6" class="px-6 py-8 text-center text-slate-500">Belum ada data ruangan.</td>
</tr>
@endforelse
</tbody>
</table>
</div>
<!-- Table Pagination -->
<div class="px-6 py-4 flex items-center justify-between border-t border-slate-100" data-purpose="table-pagination">
    {{ $locations->links() }}
</div>
</section>
<!-- END: Table Container Card -->

<!-- Modal Tambah Ruangan -->
<div id="addRuanganModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('addRuanganModal').classList.add('hidden')"></div>
    
    <div class="relative w-full max-w-[450px] bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] font-sans">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
            <h2 class="text-lg font-bold text-slate-900">Tambah Ruangan</h2>
            <button type="button" onclick="document.getElementById('addRuanganModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('locations.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="px-6 py-5 space-y-4 overflow-y-auto">
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Kode Ruangan</label>
                    <input type="text" name="code" required placeholder="Contoh: R-LAB-01" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Nama Ruangan</label>
                    <input type="text" name="name" required placeholder="Masukkan nama ruangan" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Penanggung Jawab</label>
                    <input type="text" name="penanggung_jawab" placeholder="Masukkan nama penanggung jawab" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Keterangan</label>
                    <textarea name="description" rows="3" placeholder="Keterangan atau lokasi ruangan" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400"></textarea>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 mt-auto">
                <button type="button" onclick="document.getElementById('addRuanganModal').classList.add('hidden')" class="px-5 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
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
<!-- Modal Edit Ruangan -->
<div id="editRuanganModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('editRuanganModal').classList.add('hidden')"></div>
    
    <div class="relative w-full max-w-[450px] bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] font-sans">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
            <h2 class="text-lg font-bold text-slate-900">Edit Ruangan</h2>
            <button type="button" onclick="document.getElementById('editRuanganModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="editRuanganForm" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            @method('PUT')
            <div class="px-6 py-5 space-y-4 overflow-y-auto">
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Kode Ruangan</label>
                    <input type="text" id="edit_code" name="code" required placeholder="Contoh: R-LAB-01" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Nama Ruangan</label>
                    <input type="text" id="edit_name" name="name" required placeholder="Masukkan nama ruangan" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Penanggung Jawab</label>
                    <input type="text" id="edit_penanggung_jawab" name="penanggung_jawab" placeholder="Masukkan nama penanggung jawab" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Keterangan</label>
                    <textarea id="edit_description" name="description" rows="3" placeholder="Keterangan atau lokasi ruangan" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400"></textarea>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 mt-auto">
                <button type="button" onclick="document.getElementById('editRuanganModal').classList.add('hidden')" class="px-5 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
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
    function openEditRuanganModal(id, code, name, penanggung_jawab, description) {
        document.getElementById('editRuanganForm').action = `/locations/${id}`;
        document.getElementById('edit_code').value = code;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_penanggung_jawab').value = penanggung_jawab;
        document.getElementById('edit_description').value = description;
        document.getElementById('editRuanganModal').classList.remove('hidden');
    }
</script>
@endpush