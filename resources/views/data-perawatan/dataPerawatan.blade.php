@extends('layouts.app')

@section('title', 'Data Perawatan Aset')

@section('content')
<!-- Page Title Area -->
<div class="flex items-start justify-between mb-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Data Perawatan Aset</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola riwayat perawatan dan pemeliharaan perangkat NOC.</p>
    </div>
    <button onclick="document.getElementById('addPerawatanModal').classList.remove('hidden')" class="bg-[#3F51B5] hover:bg-[#303F9F] text-white px-5 py-2.5 rounded-lg text-sm font-medium flex items-center shadow-sm transition-all">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Ajukan Perawatan
    </button>
</div>

<!-- BEGIN: Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6">
    <!-- Total Perawatan -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex items-center">
        <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center mr-4 flex-shrink-0">
            <i data-lucide="file-text" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Perawatan</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($totalPerawatan) }}</p>
        </div>
    </div>

    <!-- Menunggu Persetujuan -->
    <a href="{{ route('perawatan.index', ['status' => 'menunggu']) }}" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex items-center hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center mr-4 flex-shrink-0">
            <i data-lucide="clock" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Menunggu Persetujuan</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($menungguPersetujuan) }}</p>
        </div>
    </a>

    <!-- Sedang Berlangsung -->
    <a href="{{ route('perawatan.index', ['status' => 'proses']) }}" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex items-center hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mr-4 flex-shrink-0">
            <i data-lucide="settings" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sedang Berlangsung</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($sedangBerlangsung) }}</p>
        </div>
    </a>

    <!-- Selesai -->
    <a href="{{ route('perawatan.index', ['status' => 'selesai']) }}" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex items-center hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center mr-4 flex-shrink-0">
            <i data-lucide="check" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Selesai</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($selesai) }}</p>
        </div>
    </a>
</div>
<!-- END: Summary Cards -->

<!-- Main Table Section -->
<section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <!-- Header -->
    <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
        <!-- Search Form -->
        <form action="{{ route('perawatan.index') }}" method="GET" class="flex items-center gap-2 flex-1 w-full sm:w-auto">
            <div class="relative w-full sm:w-72">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                    <i class="w-4 h-4" data-lucide="search"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari perawatan...">
            </div>
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <button type="submit" class="px-3 py-2 bg-slate-100 text-slate-600 rounded-lg text-sm hover:bg-slate-200 transition-colors">Cari</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('perawatan.index') }}" class="px-3 py-2 text-slate-500 text-sm hover:text-slate-700">Reset</a>
            @endif
        </form>
        
        <div class="flex items-center space-x-3">
            <!-- Filter Dropdown -->
            <div class="relative">
                <button onclick="document.getElementById('filterMenu').classList.toggle('hidden')" class="flex items-center px-4 py-2 border border-slate-200 rounded-lg text-sm text-slate-600 hover:bg-slate-50">
                    <i data-lucide="filter" class="w-4 h-4 mr-2"></i> Filter
                    @if(request('status'))
                        <span class="ml-1.5 w-2 h-2 bg-blue-500 rounded-full"></span>
                    @endif
                </button>
                <div id="filterMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 z-50 overflow-hidden">
                    <a href="{{ route('perawatan.index', array_merge(request()->only('search'), [])) }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 {{ !request('status') ? 'font-bold bg-slate-50' : '' }}">Semua Status</a>
                    <a href="{{ route('perawatan.index', array_merge(request()->only('search'), ['status' => 'menunggu'])) }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 {{ request('status') == 'menunggu' ? 'font-bold bg-slate-50' : '' }}">Menunggu</a>
                    <a href="{{ route('perawatan.index', array_merge(request()->only('search'), ['status' => 'proses'])) }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 {{ request('status') == 'proses' ? 'font-bold bg-slate-50' : '' }}">Sedang Berlangsung</a>
                    <a href="{{ route('perawatan.index', array_merge(request()->only('search'), ['status' => 'selesai'])) }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 {{ request('status') == 'selesai' ? 'font-bold bg-slate-50' : '' }}">Selesai</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap" id="perawatan-table">
            <thead class="bg-slate-50/50">
                <tr>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">ID Aset</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Barang</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis Perawatan</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal Pengajuan</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Teknisi</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($perawatans as $p)
                <tr class="{{ $loop->even ? 'bg-slate-50/30' : '' }} table-row-hover transition-colors">
                    <td class="px-6 py-4 text-sm text-slate-600 font-medium">{{ $p->item->code ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $p->item->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $p->jenis_perawatan }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $p->tanggal_pengajuan->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $p->user->name ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @if($p->status == 'menunggu')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-50 text-orange-600 border border-orange-100">
                            <span class="w-2 h-2 bg-orange-500 rounded-full mr-1.5"></span>
                            {{ $p->status_label }}
                        </span>
                        @elseif($p->status == 'proses')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-1.5"></span>
                            {{ $p->status_label }}
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span>
                            {{ $p->status_label }}
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center space-x-3">
                            <!-- View Detail -->
                            <button onclick="showDetailModal({{ $p->id }})" class="text-slate-500 hover:text-blue-600" title="Lihat Detail">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            <!-- Edit / Update Status -->
                            <button onclick="showEditModal({{ $p->id }}, '{{ $p->status }}', '{{ $p->jenis_perawatan }}', `{!! addslashes($p->catatan ?? '') !!}`)" class="text-slate-500 hover:text-indigo-600" title="Edit / Update Status">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </button>
                            @if(auth()->user()->role === 'Superadmin')
                            <!-- Delete -->
                            <form action="{{ route('perawatan.destroy', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data perawatan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600" title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-slate-500">
                        <div class="flex flex-col items-center justify-center">
                            <i data-lucide="inbox" class="w-10 h-10 text-slate-300 mb-3"></i>
                            <p class="text-sm font-medium">Belum ada data perawatan</p>
                            <p class="text-xs mt-1 text-slate-400">Data perawatan yang diajukan akan muncul di sini</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($perawatans->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $perawatans->links() }}
    </div>
    @endif
</section>

<!-- Modal Ajukan Perawatan -->
<div id="addPerawatanModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('addPerawatanModal').classList.add('hidden')"></div>
    <div class="relative w-full max-w-[500px] bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] font-sans">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
            <h2 class="text-lg font-bold text-slate-900">Form Pengajuan Perawatan</h2>
            <button onclick="document.getElementById('addPerawatanModal').classList.add('hidden')" type="button" class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('perawatan.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="px-6 py-5 space-y-4 overflow-y-auto">
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Barang / Aset</label>
                    <select name="item_id" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] bg-white">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}">[{{ $item->code }}] {{ $item->name }} - {{ $item->brand ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Jenis Perawatan</label>
                    <select name="jenis_perawatan" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] bg-white">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="Pengecekan Rutin (Preventive)">Pengecekan Rutin (Preventive)</option>
                        <option value="Perbaikan (Corrective)">Perbaikan Kerusakan (Corrective)</option>
                        <option value="Pembaruan/Upgrade">Upgrade / Pembaruan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Tanggal Pengajuan</label>
                    <input type="date" name="tanggal_pengajuan" required value="{{ date('Y-m-d') }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5]">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Catatan Keluhan / Deskripsi</label>
                    <textarea name="catatan" rows="3" placeholder="Jelaskan secara singkat alasan perawatan atau keluhan barang..." class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5]"></textarea>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('addPerawatanModal').classList.add('hidden')" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-[#3F51B5] text-white font-medium rounded-lg hover:bg-[#303F9F] transition-colors shadow-sm text-sm flex items-center">
                    <i data-lucide="save" class="w-4 h-4 mr-2"></i> Simpan Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail Perawatan -->
<div id="detailModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="document.getElementById('detailModal').classList.add('hidden')"></div>
    <div class="relative w-full max-w-[500px] bg-white rounded-xl shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="text-lg font-bold text-slate-900">Detail Perawatan</h2>
            <button onclick="document.getElementById('detailModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div id="detailContent" class="px-6 py-5 space-y-3 max-h-[70vh] overflow-y-auto">
            <p class="text-slate-500 text-sm">Memuat data...</p>
        </div>
    </div>
</div>

<!-- Modal Edit / Update Status Perawatan -->
<div id="editModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="document.getElementById('editModal').classList.add('hidden')"></div>
    <div class="relative w-full max-w-[500px] bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="text-lg font-bold text-slate-900">Update Perawatan</h2>
            <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form id="editForm" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            @method('PUT')
            <div class="px-6 py-5 space-y-4 overflow-y-auto">
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Status Perawatan</label>
                    <select name="status" id="editStatus" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] bg-white">
                        <option value="menunggu">Menunggu Persetujuan</option>
                        <option value="proses">Sedang Berlangsung</option>
                        <option value="selesai">Selesai</option>
                    </select>
                    <p class="text-xs text-slate-400 mt-1">
                        <span class="text-orange-500">Menunggu</span> → <span class="text-blue-500">Berlangsung</span> akan set status barang ke <b>maintenance</b>.<br>
                        → <span class="text-green-500">Selesai</span> akan kembalikan status barang ke <b>tersedia</b>.
                    </p>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Jenis Perawatan</label>
                    <input type="text" name="jenis_perawatan" id="editJenis" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5]">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Catatan</label>
                    <textarea name="catatan" id="editCatatan" rows="3" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5]"></textarea>
                </div>
                <div class="space-y-1.5" id="editTanggalSelesaiWrap">
                    <label class="block text-sm font-bold text-slate-700">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="editTanggalSelesai" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5]">
                    <p class="text-xs text-slate-400">Otomatis terisi jika status = Selesai</p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-[#3F51B5] text-white font-medium rounded-lg hover:bg-[#303F9F] transition-colors shadow-sm text-sm flex items-center">
                    <i data-lucide="save" class="w-4 h-4 mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });

    function showDetailModal(id) {
        const modal = document.getElementById('detailModal');
        const content = document.getElementById('detailContent');
        content.innerHTML = '<p class="text-slate-500 text-sm">Memuat data...</p>';
        modal.classList.remove('hidden');

        fetch('/data-perawatan/' + id)
            .then(res => res.json())
            .then(data => {
                const statusColors = { menunggu: 'orange', proses: 'blue', selesai: 'green' };
                const statusLabels = { menunggu: 'Menunggu Persetujuan', proses: 'Sedang Berlangsung', selesai: 'Selesai' };
                const sc = statusColors[data.status] || 'slate';
                const sl = statusLabels[data.status] || data.status;

                content.innerHTML = `
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-${sc}-50 text-${sc}-600 border border-${sc}-100">
                                <span class="w-2 h-2 bg-${sc}-500 rounded-full mr-1.5"></span>${sl}
                            </span>
                            <span class="text-xs text-slate-400">ID: #${data.id}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div><p class="text-[10px] font-bold text-slate-400 uppercase">Kode Aset</p><p class="text-sm font-semibold text-slate-800">${data.item?.code || '-'}</p></div>
                            <div><p class="text-[10px] font-bold text-slate-400 uppercase">Nama Barang</p><p class="text-sm font-semibold text-slate-800">${data.item?.name || '-'}</p></div>
                            <div><p class="text-[10px] font-bold text-slate-400 uppercase">Kategori</p><p class="text-sm text-slate-700">${data.item?.category?.name || '-'}</p></div>
                            <div><p class="text-[10px] font-bold text-slate-400 uppercase">Jenis Perawatan</p><p class="text-sm text-slate-700">${data.jenis_perawatan}</p></div>
                            <div><p class="text-[10px] font-bold text-slate-400 uppercase">Tanggal Pengajuan</p><p class="text-sm text-slate-700">${data.tanggal_pengajuan}</p></div>
                            <div><p class="text-[10px] font-bold text-slate-400 uppercase">Tanggal Selesai</p><p class="text-sm text-slate-700">${data.tanggal_selesai || '-'}</p></div>
                            <div><p class="text-[10px] font-bold text-slate-400 uppercase">Diajukan Oleh</p><p class="text-sm text-slate-700">${data.user?.name || '-'}</p></div>
                        </div>
                        <div><p class="text-[10px] font-bold text-slate-400 uppercase">Catatan</p><p class="text-sm text-slate-600 mt-1">${data.catatan || 'Tidak ada catatan'}</p></div>
                    </div>
                `;
                if (typeof lucide !== 'undefined') lucide.createIcons();
            })
            .catch(() => {
                content.innerHTML = '<p class="text-red-500 text-sm">Gagal memuat data.</p>';
            });
    }

    function showEditModal(id, status, jenis, catatan) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        form.action = '/data-perawatan/' + id;
        document.getElementById('editStatus').value = status;
        document.getElementById('editJenis').value = jenis;
        document.getElementById('editCatatan').value = catatan;
        document.getElementById('editTanggalSelesai').value = '';
        modal.classList.remove('hidden');
    }
</script>
@endpush
@endsection
