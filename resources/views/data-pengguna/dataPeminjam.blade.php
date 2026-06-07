@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')
    <!-- BEGIN: Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Data Peminjaman</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola dan pantau seluruh riwayat peminjaman aset.</p>
        </div>
        <a href="{{ route('qr.admin') }}" class="flex items-center justify-center gap-2 px-6 py-2.5 bg-[#3F51B5] text-white font-semibold rounded-lg hover:bg-[#3949AB] transition-all shadow-sm active:scale-95 text-sm border-none cursor-pointer no-underline">
            <span class="material-symbols-outlined text-[20px]">add</span>
            TAMBAH PEMINJAMAN
        </a>
    </div>

    <!-- BEGIN: Bento Layout Grid -->
    <div class="grid grid-cols-12 gap-6 mb-6">
        <!-- Filter Controls -->
        <div class="col-span-12 lg:col-span-8 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col gap-4">
            <form action="{{ route('peminjaman.index') }}" method="GET" class="flex flex-col gap-4 w-full">
                <!-- Row 1: Jurusan & Button -->
                <div class="flex flex-col md:flex-row items-start md:items-end gap-3 w-full">
                    <div class="space-y-1.5 flex-grow w-full">
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest">FILTER JURUSAN</label>
                        <div class="relative">
                            <select name="jurusan" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] outline-none transition-all bg-gray-50 cursor-pointer text-gray-600 appearance-none">
                                <option value="Semua Jurusan">Semua Jurusan</option>
                                <option value="TKJ" {{ request('jurusan') == 'TKJ' ? 'selected' : '' }}>TKJ - Teknik Komputer Jaringan</option>
                                <option value="RPL" {{ request('jurusan') == 'RPL' ? 'selected' : '' }}>RPL - Rekayasa Perangkat Lunak</option>
                                <option value="MM" {{ request('jurusan') == 'MM' ? 'selected' : '' }}>MM - Multimedia</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-[20px]">expand_more</span>
                        </div>
                    </div>
                    <button type="submit" class="bg-[#3F51B5] hover:bg-[#303F9F] text-white px-6 py-2 rounded-xl flex items-center justify-center gap-2 transition-all w-full md:w-auto text-sm font-bold shadow-sm active:scale-95 border-none cursor-pointer h-[38px]">
                        <span class="material-symbols-outlined text-[18px]">filter_list</span>
                        Terapkan
                    </button>
                    @if(request()->hasAny(['jurusan', 'start_date', 'end_date']))
                        <a href="{{ route('peminjaman.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-6 py-2 rounded-xl flex items-center justify-center gap-2 transition-all w-full md:w-auto text-sm font-bold shadow-sm active:scale-95 border-none cursor-pointer h-[38px]">
                            Reset
                        </a>
                    @endif
                </div>

                <!-- Row 2: Rentang Tanggal (Below Row 1) -->
                <div class="space-y-1.5 pt-3 border-t border-gray-50">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest">RENTANG TANGGAL</label>
                    <div class="flex items-center gap-2 w-full md:max-w-md">
                        <div class="relative flex-1">
                            <input name="start_date" value="{{ request('start_date') }}" class="w-full pl-3 pr-2 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] outline-none transition-all bg-gray-50 text-gray-600" type="date" placeholder="dd/mm/yyyy"/>
                        </div>
                        <span class="text-gray-400 font-bold text-[10px] shrink-0 px-0.5">s/d</span>
                        <div class="relative flex-1">
                            <input name="end_date" value="{{ request('end_date') }}" class="w-full pl-3 pr-2 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] outline-none transition-all bg-gray-50 text-gray-600" type="date" placeholder="dd/mm/yyyy"/>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Quick Metrics Card -->
        <div class="col-span-12 lg:col-span-4 bg-white rounded-2xl border border-gray-100 p-6 flex items-start justify-between shadow-sm hover:shadow-md transition-all relative overflow-hidden before:absolute before:top-0 before:left-0 before:right-0 before:h-[3px] before:bg-[#3F51B5]">
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Dipinjam</h3>
                <div class="text-3xl font-extrabold text-gray-800 mt-2">{{ $totalDipinjam ?? 0 }}</div>
                <div class="text-xs text-green-500 font-bold mt-2 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                    Aktif Hari Ini
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-[#EDE7F6] text-[#3F51B5] flex-shrink-0">
                <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1;">inventory</span>
            </div>
        </div>
    </div>

    <!-- BEGIN: Data Table Container -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-all">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">ID PINJAM</th>
                        <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">PEMINJAM</th>
                        <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">JURUSAN</th>
                        <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">NAMA BARANG</th>
                        <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">TGL PINJAM</th>
                        <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">TGL KEMBALI</th>
                        <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">STATUS</th>
                        <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($peminjamans as $pinjam)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-[#3F51B5] text-xs">PJ-{{ str_pad($pinjam->id_pinjam, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4 font-bold text-gray-800 text-sm">{{ $pinjam->nama_peminjam }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-bold rounded border border-blue-100">{{ $pinjam->kelas ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ $pinjam->item->name ?? $pinjam->item_code }}</td>
                        <td class="px-6 py-4 text-xs text-gray-500 font-medium text-center">{{ $pinjam->waktu_pinjam ? $pinjam->waktu_pinjam->format('d M Y') : '-' }}</td>
                        <td class="px-6 py-4 text-xs text-gray-500 font-medium text-center">{{ $pinjam->waktu_kembali ? $pinjam->waktu_kembali->format('d M Y') : '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($pinjam->status == 'dipinjam')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-700 text-[10px] font-bold rounded-full border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Dipinjam
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-[10px] font-bold rounded-full border border-green-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Kembali
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                @if($pinjam->status == 'dipinjam')
                                <form action="{{ route('peminjaman.return', $pinjam->id_pinjam) }}" method="POST" onsubmit="return confirm('Tandai barang ini telah dikembalikan?');">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 bg-green-500 text-white hover:bg-green-600 rounded-lg transition-all shadow-sm active:scale-95 border-none cursor-pointer">
                                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                        <span class="text-[11px] font-bold tracking-wide">KEMBALIKAN</span>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('peminjaman.destroy', $pinjam->id_pinjam) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data peminjaman ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus Data" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500 italic">Belum ada data peminjaman.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination & Info -->
        <div class="px-6 py-4 flex items-center justify-between bg-gray-50 border-t border-gray-100">
            <p class="text-xs text-gray-500 font-bold">Menampilkan {{ $peminjamans->firstItem() ?? 0 }}-{{ $peminjamans->lastItem() ?? 0 }} dari {{ $peminjamans->total() }} data</p>
            <div class="flex items-center gap-1.5">
                {{ $peminjamans->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
@endsection
