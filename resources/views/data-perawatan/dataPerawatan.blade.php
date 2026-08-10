@extends('layouts.app')

@section('title', 'Data Perawatan Aset')

@push('styles')
<style>
    /* Custom Scrollbar for autocomplete */
    #item_autocomplete_dropdown::-webkit-scrollbar { width: 6px; }
    #item_autocomplete_dropdown::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 0.5rem; }
    #item_autocomplete_dropdown::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 0.5rem; }
    #item_autocomplete_dropdown::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endpush

@section('content')
<!-- Page Title Area -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Data Perawatan Aset</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola riwayat perawatan dan pemeliharaan perangkat NOC.</p>
    </div>
    <button onclick="document.getElementById('addPerawatanModal').classList.remove('hidden')" class="flex items-center gap-2 px-4 py-2 bg-[#3F51B5] text-white font-semibold rounded-lg hover:bg-[#3949AB] transition-all shadow-sm active:scale-95 text-sm">
        <span class="material-symbols-outlined text-[18px]">add</span>
        Ajukan Perawatan
    </button>
</div>

<!-- BEGIN: Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
    <!-- Total Perawatan -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center">
        <div class="w-12 h-12 rounded-xl bg-gray-50 text-gray-600 flex items-center justify-center mr-4 flex-shrink-0">
            <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1;">description</span>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Perawatan</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalPerawatan) }}</p>
        </div>
    </div>

    <!-- Menunggu Persetujuan -->
    <a href="{{ route('perawatan.index', ['status' => 'menunggu']) }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center mr-4 flex-shrink-0">
            <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1;">schedule</span>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Menunggu Persetujuan</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($menungguPersetujuan) }}</p>
        </div>
    </a>

    <!-- Sedang Berlangsung -->
    <a href="{{ route('perawatan.index', ['status' => 'proses']) }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mr-4 flex-shrink-0">
            <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1;">build</span>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Sedang Berlangsung</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($sedangBerlangsung) }}</p>
        </div>
    </a>

    <!-- Selesai -->
    <a href="{{ route('perawatan.index', ['status' => 'selesai']) }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center mr-4 flex-shrink-0">
            <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Selesai</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($selesai) }}</p>
        </div>
    </a>
</div>
<!-- END: Summary Cards -->

<!-- Filter Bar -->
<form action="{{ route('perawatan.index') }}" method="GET" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-wrap items-center gap-3">
    <div class="relative grow min-w-50">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
        <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] outline-none transition-all" placeholder="Cari perawatan...">
    </div>
    @if(request('status'))
        <input type="hidden" name="status" value="{{ request('status') }}">
    @endif
    <div class="relative">
        <button onclick="event.preventDefault(); document.getElementById('filterMenu').classList.toggle('hidden')" class="flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-xl text-sm text-gray-700 hover:bg-gray-50 transition-colors">
            <span class="material-symbols-outlined text-[18px]">filter_list</span>
            Filter
            @if(request('status'))
                <span class="ml-1.5 w-2 h-2 bg-blue-500 rounded-full"></span>
            @endif
        </button>
        <div id="filterMenu" class="hidden absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden">
            <a href="{{ route('perawatan.index', array_merge(request()->only('search'), [])) }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 {{ !request('status') ? 'font-semibold bg-gray-50 text-[#3F51B5]' : '' }}">Semua Status</a>
            <a href="{{ route('perawatan.index', array_merge(request()->only('search'), ['status' => 'menunggu'])) }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 {{ request('status') == 'menunggu' ? 'font-semibold bg-gray-50 text-[#3F51B5]' : '' }}">Menunggu</a>
            <a href="{{ route('perawatan.index', array_merge(request()->only('search'), ['status' => 'proses'])) }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 {{ request('status') == 'proses' ? 'font-semibold bg-gray-50 text-[#3F51B5]' : '' }}">Sedang Berlangsung</a>
            <a href="{{ route('perawatan.index', array_merge(request()->only('search'), ['status' => 'selesai'])) }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 {{ request('status') == 'selesai' ? 'font-semibold bg-gray-50 text-[#3F51B5]' : '' }}">Selesai</a>
        </div>
    </div>
    <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all text-sm border border-gray-200">
        <span class="material-symbols-outlined text-[16px]">search</span> Cari
    </button>
    @if(request()->hasAny(['search', 'status']))
        <a href="{{ route('perawatan.index') }}" class="flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 font-semibold rounded-xl hover:bg-red-100 transition-all text-sm border border-red-200">
            <span class="material-symbols-outlined text-[16px]">close</span> Reset
        </a>
    @endif
</form>

<!-- Main Table Section -->
<section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Main Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider w-12 text-center">No</th>
                    <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">ID Aset</th>
                    <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Barang</th>
                    <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis Perawatan</th>
                    <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Pengajuan</th>
                    <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Teknisi</th>
                    <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($perawatans as $p)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-sm text-gray-500 text-center">{{ $perawatans->firstItem() + $loop->index }}</td>
                    <td class="py-4 px-6 text-sm text-gray-600 font-semibold">
                        <code class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded font-mono font-bold">{{ $p->item->code ?? '-' }}</code>
                    </td>
                    <td class="py-4 px-6 font-semibold text-sm text-gray-800">{{ $p->item->name ?? '-' }}</td>
                    <td class="py-4 px-6 text-sm text-gray-600">{{ $p->jenis_perawatan }}</td>
                    <td class="py-4 px-6 text-sm text-gray-600">{{ $p->tanggal_pengajuan->format('d M Y') }}</td>
                    <td class="py-4 px-6 text-sm text-gray-600">{{ $p->user->name ?? '-' }}</td>
                    <td class="py-4 px-6">
                        @if($p->status == 'menunggu')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-orange-50 text-orange-600 border border-orange-100">
                            <span class="w-2 h-2 bg-orange-500 rounded-full mr-1.5"></span>
                            {{ $p->status_label }}
                        </span>
                        @elseif($p->status == 'proses')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-1.5"></span>
                            {{ $p->status_label }}
                        </span>
                        @elseif($p->status == 'menunggu_pengecekan')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-100">
                            <span class="w-2 h-2 bg-purple-500 rounded-full mr-1.5"></span>
                            {{ $p->status_label }}
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-50 text-green-700 border border-green-100">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span>
                            {{ $p->status_label }}
                        </span>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center justify-center gap-2">
                            <!-- Show detail / copy link -->
                            @if(auth()->user()->role === 'Superadmin')
                                @if(in_array($p->status, ['menunggu', 'proses']))
                                    @if($p->token_link)
                                        <button onclick="copyToClipboard('{{ route('maintenance.public_form', $p->token_link) }}')" class="p-1.5 text-gray-400 hover:text-indigo-500 hover:bg-indigo-50 rounded-lg transition-colors" title="Copy Link Form Teknisi">
                                            <span class="material-symbols-outlined text-[20px]">content_copy</span>
                                        </button>
                                    @else
                                        <form action="{{ route('perawatan.generate-link', $p->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-indigo-500 hover:bg-indigo-50 rounded-lg transition-colors" title="Generate Link Laporan Teknisi">
                                                <span class="material-symbols-outlined text-[20px]">add_link</span>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                                
                                @if($p->status == 'menunggu_pengecekan')
                                    <button onclick="showVerifyModal({{ $p->id }}, '{{ $p->teknisi_nama }}', '{{ $p->biaya }}', '{{ asset('storage/' . $p->foto_bukti) }}')" class="p-1.5 text-purple-600 bg-purple-100 hover:bg-purple-200 rounded-lg transition-colors shadow-sm" title="Verifikasi Laporan">
                                        <span class="material-symbols-outlined text-[20px]">fact_check</span>
                                    </button>
                                @endif
                            @endif

                            <button onclick="showDetailModal({{ $p->id }})" class="p-1.5 text-gray-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </button>
                            <button onclick="showEditModal({{ $p->id }}, '{{ $p->status }}', '{{ $p->jenis_perawatan }}', `{!! addslashes($p->catatan ?? '') !!}`, '{{ $p->item->status ?? '' }}', '{{ $p->item->condition ?? '' }}')" class="p-1.5 text-gray-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-colors" title="Edit / Update Status">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </button>
                            @if(auth()->user()->role === 'Superadmin')
                            <form action="{{ route('perawatan.destroy', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data perawatan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-24 text-center text-gray-400">
                        <span class="material-symbols-outlined text-[64px] mb-4 opacity-20">inbox</span>
                        <div class="font-semibold text-gray-600">Belum ada data perawatan</div>
                        <div class="text-xs mt-1">Data perawatan yang diajukan akan muncul di sini</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($perawatans->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
        {{ $perawatans->appends(request()->query())->links() }}
    </div>
    @endif
</section>
@endsection

@push('page-modals')
<!-- Modal Ajukan Perawatan -->
<div id="addPerawatanModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('addPerawatanModal').classList.add('hidden')"></div>
    <div class="relative w-full max-w-[500px] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] font-sans">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#3F51B5] to-[#5C6BC0]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-[22px]">build</span>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-white">Form Pengajuan Perawatan</h2>
                    <p class="text-xs text-white/70 mt-0.5">Ajukan perawatan untuk aset yang bermasalah</p>
                </div>
            </div>
            <button onclick="document.getElementById('addPerawatanModal').classList.add('hidden')" type="button" class="text-white/70 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form action="{{ route('perawatan.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="px-6 py-5 space-y-4 overflow-y-auto">
                <div class="space-y-1.5 relative">
                    <label class="block text-[13px] font-semibold text-gray-700">Barang / Aset <span class="text-red-500">*</span></label>
                    <input type="text" id="item_search_input" required placeholder="Ketik nama atau kode barang..." autocomplete="off"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 focus:ring-[#3F51B5] focus:border-[#3F51B5] bg-white transition-all shadow-sm">
                    <input type="hidden" name="item_id" id="hidden_item_id" required>
                    
                    <!-- Autocomplete Dropdown -->
                    <div id="item_autocomplete_dropdown" class="absolute z-[110] w-full bg-white border border-gray-200 rounded-lg shadow-lg mt-1 hidden max-h-48 overflow-y-auto">
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[13px] font-semibold text-gray-700">Jenis Perawatan <span class="text-red-500">*</span></label>
                    <select name="jenis_perawatan" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 focus:ring-[#3F51B5] focus:border-[#3F51B5] bg-white cursor-pointer shadow-sm">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="Pengecekan Rutin (Preventive)">Pengecekan Rutin (Preventive)</option>
                        <option value="Perbaikan (Corrective)">Perbaikan Kerusakan (Corrective)</option>
                        <option value="Pembaruan/Upgrade">Upgrade / Pembaruan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[13px] font-semibold text-gray-700">Tanggal Pengajuan <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_pengajuan" required value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 focus:ring-[#3F51B5] focus:border-[#3F51B5] shadow-sm">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[13px] font-semibold text-gray-700">Catatan Keluhan / Deskripsi</label>
                    <textarea name="catatan" rows="3" placeholder="Jelaskan secara singkat alasan perawatan atau keluhan barang..." class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 focus:ring-[#3F51B5] focus:border-[#3F51B5] shadow-sm"></textarea>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3 mt-auto">
                <button type="button" onclick="document.getElementById('addPerawatanModal').classList.add('hidden')" class="px-5 py-2.5 text-[13px] font-bold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2.5 text-[13px] font-bold text-white bg-[#3F51B5] rounded-lg hover:bg-[#3949AB] transition-colors shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">save</span> Simpan Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail Perawatan -->
<div id="detailModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="document.getElementById('detailModal').classList.add('hidden')"></div>
    <div class="relative w-full max-w-[500px] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col font-sans max-h-[90vh]">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Detail Perawatan</h2>
                <p class="text-xs text-gray-500 mt-0.5">Informasi lengkap pengajuan perawatan</p>
            </div>
            <button onclick="document.getElementById('detailModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <div id="detailContent" class="px-6 py-5 space-y-3 overflow-y-auto">
            <p class="text-gray-500 text-sm">Memuat data...</p>
        </div>
    </div>
</div>

<!-- Modal Edit / Update Status Perawatan -->
<div id="editModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="document.getElementById('editModal').classList.add('hidden')"></div>
    <div class="relative w-full max-w-[500px] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] font-sans">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#3F51B5] to-[#5C6BC0]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-[22px]">edit_note</span>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-white">Update Perawatan</h2>
                    <p class="text-xs text-white/70 mt-0.5">Perbarui status dan detail perawatan</p>
                </div>
            </div>
            <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-white/70 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form id="editForm" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            @method('PUT')
            <div class="px-6 py-5 space-y-4 overflow-y-auto">
                <div class="space-y-1.5">
                    <label class="block text-[13px] font-semibold text-gray-700">Status Perawatan <span class="text-red-500">*</span></label>
                    <select name="status" id="editStatus" required onchange="toggleItemUpdates()" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 focus:ring-[#3F51B5] focus:border-[#3F51B5] bg-white cursor-pointer shadow-sm">
                        <option value="menunggu">Menunggu Persetujuan</option>
                        <option value="proses">Sedang Berlangsung</option>
                        <option value="selesai">Selesai</option>
                    </select>
                    <p class="text-[11px] text-gray-400 mt-1.5">
                        <span class="text-orange-500 font-semibold">Menunggu</span> → <span class="text-blue-500 font-semibold">Berlangsung</span> akan set status barang ke <b>maintenance</b>.<br>
                        → <span class="text-green-500 font-semibold">Selesai</span> memungkinkan Anda memperbarui kondisi dan status akhir barang.
                    </p>
                </div>
                
                <div id="itemUpdatesWrap" class="hidden p-4 rounded-xl bg-blue-50 border border-blue-100 space-y-4">
                    <h4 class="text-[11px] font-bold text-blue-800 uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">task_alt</span>
                        Update Kondisi Barang (Setelah Selesai)
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-[13px] font-semibold text-gray-700">Kondisi Akhir</label>
                            <select name="item_condition" id="editItemCondition" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 focus:ring-[#3F51B5] bg-white cursor-pointer shadow-sm">
                                <option value="baik">Baik</option>
                                <option value="rusak_ringan">Rusak Ringan</option>
                                <option value="rusak_berat">Rusak Berat</option>
                                <option value="hilang">Hilang</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[13px] font-semibold text-gray-700">Status Barang Akhir</label>
                            <select name="item_status" id="editItemStatus" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 focus:ring-[#3F51B5] bg-white cursor-pointer shadow-sm">
                                <option value="tersedia">Tersedia</option>
                                <option value="dimusnahkan">Dimusnahkan</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[13px] font-semibold text-gray-700">Jenis Perawatan</label>
                    <input type="text" name="jenis_perawatan" id="editJenis" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 focus:ring-[#3F51B5] focus:border-[#3F51B5] shadow-sm">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[13px] font-semibold text-gray-700">Catatan</label>
                    <textarea name="catatan" id="editCatatan" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 focus:ring-[#3F51B5] focus:border-[#3F51B5] shadow-sm"></textarea>
                </div>
                <div class="space-y-1.5" id="editTanggalSelesaiWrap">
                    <label class="block text-[13px] font-semibold text-gray-700">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="editTanggalSelesai" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 focus:ring-[#3F51B5] focus:border-[#3F51B5] shadow-sm">
                    <p class="text-[11px] text-gray-400 mt-1">Otomatis terisi jika status = Selesai</p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3 mt-auto">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-5 py-2.5 text-[13px] font-bold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2.5 text-[13px] font-bold text-white bg-[#3F51B5] rounded-lg hover:bg-[#3949AB] transition-colors shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">save</span> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Verifikasi Perawatan (Dari Teknisi) -->
<div id="verifyModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="document.getElementById('verifyModal').classList.add('hidden')"></div>
    <div class="relative w-full max-w-[600px] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] font-sans">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-600 to-purple-500">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-[22px]">fact_check</span>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-white">Verifikasi Laporan Perbaikan</h2>
                    <p class="text-xs text-white/70 mt-0.5">Cek hasil kerja teknisi dan ubah status aset</p>
                </div>
            </div>
            <button onclick="document.getElementById('verifyModal').classList.add('hidden')" class="text-white/70 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form id="verifyForm" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="px-6 py-5 space-y-5 overflow-y-auto">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Nama Teknisi</p>
                        <p id="verifyTeknisiNama" class="text-sm font-semibold text-gray-800 mt-1">-</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Biaya</p>
                        <p id="verifyBiaya" class="text-sm font-semibold text-gray-800 mt-1">Rp 0</p>
                    </div>
                </div>

                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Foto Bukti Perbaikan / Nota</p>
                    <a id="verifyFotoLink" href="#" target="_blank" class="block w-full max-w-[200px] border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <img id="verifyFotoImg" src="" alt="Bukti Perbaikan" class="w-full h-auto object-cover">
                    </a>
                </div>

                <hr class="border-gray-100">

                <div class="space-y-1.5">
                    <label class="block text-[13px] font-semibold text-gray-700">Keputusan <span class="text-red-500">*</span></label>
                    <select name="action" id="verifyAction" required onchange="toggleVerifyItemUpdates()" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 focus:ring-purple-500 bg-white cursor-pointer shadow-sm">
                        <option value="approve">Setujui & Selesaikan (Tutup Tiket)</option>
                        <option value="reject">Tolak (Kembalikan ke Proses)</option>
                    </select>
                </div>

                <div id="verifyItemUpdatesWrap" class="p-4 rounded-xl bg-purple-50 border border-purple-100 space-y-4">
                    <h4 class="text-[11px] font-bold text-purple-800 uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">inventory_2</span>
                        Update Kondisi Akhir Aset
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-[13px] font-semibold text-gray-700">Kondisi Akhir</label>
                            <select name="item_condition" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 focus:ring-purple-500 bg-white cursor-pointer shadow-sm">
                                <option value="baik">Baik</option>
                                <option value="rusak_ringan">Rusak Ringan</option>
                                <option value="rusak_berat">Rusak Berat</option>
                                <option value="hilang">Hilang</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[13px] font-semibold text-gray-700">Status Barang Akhir</label>
                            <select name="item_status" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 focus:ring-purple-500 bg-white cursor-pointer shadow-sm">
                                <option value="tersedia">Tersedia</option>
                                <option value="dimusnahkan">Dimusnahkan</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3 mt-auto">
                <button type="button" onclick="document.getElementById('verifyModal').classList.add('hidden')" class="px-5 py-2.5 text-[13px] font-bold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2.5 text-[13px] font-bold text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition-colors shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">done_all</span> Submit Keputusan
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function showDetailModal(id) {
        const modal = document.getElementById('detailModal');
        const content = document.getElementById('detailContent');
        content.innerHTML = '<p class="text-gray-500 text-sm">Memuat data...</p>';
        modal.classList.remove('hidden');

        fetch(`{{ url('data-perawatan') }}/${id}`)
            .then(res => res.json())
            .then(data => {
                const statusColors = { menunggu: 'orange', proses: 'blue', selesai: 'green' };
                const statusLabels = { menunggu: 'Menunggu Persetujuan', proses: 'Sedang Berlangsung', selesai: 'Selesai' };
                const sc = statusColors[data.status] || 'slate';
                const sl = statusLabels[data.status] || data.status;

                content.innerHTML = `
                    <div class="space-y-5">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[11px] font-bold bg-${sc}-50 text-${sc}-600 border border-${sc}-100">
                                <span class="w-2 h-2 bg-${sc}-500 rounded-full mr-1.5"></span>${sl}
                            </span>
                            <span class="text-[11px] text-gray-400 font-mono">ID: #${data.id}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div><p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Kode Aset</p><p class="text-sm font-semibold text-gray-800 mt-1">${data.item?.code || '-'}</p></div>
                            <div><p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Nama Barang</p><p class="text-sm font-semibold text-gray-800 mt-1">${data.item?.name || '-'}</p></div>
                            <div><p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Kategori</p><p class="text-sm text-gray-700 mt-1">${data.item?.category?.name || '-'}</p></div>
                            <div><p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Jenis Perawatan</p><p class="text-sm text-gray-700 mt-1">${data.jenis_perawatan}</p></div>
                            <div><p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tanggal Pengajuan</p><p class="text-sm text-gray-700 mt-1">${data.tanggal_pengajuan}</p></div>
                            <div><p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tanggal Selesai</p><p class="text-sm text-gray-700 mt-1">${data.tanggal_selesai || '-'}</p></div>
                            <div><p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Diajukan Oleh</p><p class="text-sm text-gray-700 mt-1">${data.user?.name || '-'}</p></div>
                        </div>
                        <div><p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Catatan</p><p class="text-sm text-gray-600 mt-1 leading-relaxed">${data.catatan || 'Tidak ada catatan'}</p></div>
                    </div>
                `;
            })
            .catch(() => {
                content.innerHTML = '<p class="text-red-500 text-sm">Gagal memuat data.</p>';
            });
    }

    function showEditModal(id, status, jenis, catatan, itemStatus, itemCondition) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        form.action = `{{ url('data-perawatan') }}/${id}`;
        document.getElementById('editStatus').value = status;
        document.getElementById('editJenis').value = jenis;
        document.getElementById('editCatatan').value = catatan;
        document.getElementById('editTanggalSelesai').value = '';
        
        // Handle item updates fields
        if (itemStatus && document.getElementById('editItemStatus')) {
            // Default to tersedia if it was maintenance
            document.getElementById('editItemStatus').value = itemStatus === 'maintenance' ? 'tersedia' : (itemStatus === 'dimusnahkan' ? 'dimusnahkan' : 'tersedia');
        }
        if (itemCondition && document.getElementById('editItemCondition')) {
            document.getElementById('editItemCondition').value = itemCondition;
        }
        
        toggleItemUpdates();
        modal.classList.remove('hidden');
    }
    
    function toggleItemUpdates() {
        const status = document.getElementById('editStatus').value;
        const wrap = document.getElementById('itemUpdatesWrap');
        if (status === 'selesai') {
            wrap.classList.remove('hidden');
        } else {
            wrap.classList.add('hidden');
        }
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const itemsData = @json($items ?? []);
        const searchInput = document.getElementById('item_search_input');
        const hiddenInput = document.getElementById('hidden_item_id');
        const dropdown = document.getElementById('item_autocomplete_dropdown');

        if (searchInput && dropdown) {
            searchInput.addEventListener('focus', function() {
                triggerItemAutocomplete(this.value);
            });
            
            searchInput.addEventListener('input', function() {
                triggerItemAutocomplete(this.value);
                // Reset hidden id jika text diganti manual
                hiddenInput.value = '';
            });

            function triggerItemAutocomplete(val) {
                val = val.toLowerCase();
                dropdown.innerHTML = '';
                
                let matches = itemsData;
                if (val) {
                    matches = matches.filter(i => 
                        (i.code && i.code.toLowerCase().includes(val)) || 
                        (i.name && i.name.toLowerCase().includes(val)) ||
                        (i.brand && i.brand.toLowerCase().includes(val))
                    );
                }

                if (matches.length > 0) {
                    matches.forEach(i => {
                        const div = document.createElement('div');
                        div.className = 'px-4 py-3 text-[13px] cursor-pointer hover:bg-indigo-50 flex justify-between items-center border-b border-gray-50 last:border-0 transition-colors';
                        
                        let brandText = i.brand ? ` - ${i.brand}` : '';
                        div.innerHTML = `
                            <span class="font-medium text-gray-800"><code class="text-xs bg-gray-100 text-gray-700 px-2 py-0.5 rounded font-mono mr-2">${i.code}</code>${i.name}${brandText}</span>
                            <span class="text-[10px] text-gray-500 bg-gray-50 px-2 py-0.5 rounded border border-gray-200 shadow-sm">Data Alat</span>
                        `;
                        
                        div.onclick = function() {
                            searchInput.value = `[${i.code}] ${i.name}${brandText}`;
                            hiddenInput.value = i.id;
                            dropdown.classList.add('hidden');
                        };
                        dropdown.appendChild(div);
                    });
                    dropdown.classList.remove('hidden');
                } else {
                    const noResult = document.createElement('div');
                    noResult.className = 'px-4 py-3 text-[13px] text-gray-400 text-center italic';
                    noResult.innerText = 'Barang tidak ditemukan';
                    dropdown.appendChild(noResult);
                    dropdown.classList.remove('hidden');
                }
            }

            document.addEventListener('click', function(e) {
                if (e.target !== searchInput && e.target !== dropdown && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        }
    });

    function toggleVerifyItemUpdates() {
        const action = document.getElementById('verifyAction').value;
        const wrap = document.getElementById('verifyItemUpdatesWrap');
        if(action === 'approve') {
            wrap.classList.remove('hidden');
        } else {
            wrap.classList.add('hidden');
        }
    }

    function showVerifyModal(id, teknisi, biaya, foto) {
        document.getElementById('verifyForm').action = `{{ url('data-perawatan') }}/${id}/verify`;
        document.getElementById('verifyTeknisiNama').innerText = teknisi || '-';
        document.getElementById('verifyBiaya').innerText = biaya ? 'Rp ' + parseInt(biaya).toLocaleString('id-ID') : 'Rp 0';
        document.getElementById('verifyFotoImg').src = foto;
        document.getElementById('verifyFotoLink').href = foto;
        document.getElementById('verifyAction').value = 'approve';
        toggleVerifyItemUpdates();
        document.getElementById('verifyModal').classList.remove('hidden');
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            // Simple toast alert
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-gray-800 text-white px-4 py-2 rounded-lg shadow-lg text-sm z-[200] transition-opacity duration-300';
            toast.innerText = 'Link berhasil disalin!';
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 2000);
        });
    }
</script>
@endpush
