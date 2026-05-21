@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')
    <!-- BEGIN: Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Data Peminjaman</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola dan pantau seluruh riwayat peminjaman aset.</p>
        </div>
        <button class="flex items-center justify-center gap-2 px-6 py-2.5 bg-[#3F51B5] text-white font-semibold rounded-lg hover:bg-[#3949AB] transition-all shadow-sm active:scale-95 text-sm border-none cursor-pointer">
            <span class="material-symbols-outlined text-[20px]">add</span>
            TAMBAH PEMINJAMAN
        </button>
    </div>

    <!-- BEGIN: Bento Layout Grid -->
    <div class="grid grid-cols-12 gap-6 mb-6">
        <!-- Filter Controls -->
        <div class="col-span-12 lg:col-span-8 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col gap-4">
            <!-- Row 1: Jurusan & Button -->
            <div class="flex flex-col md:flex-row items-start md:items-end gap-3 w-full">
                <div class="space-y-1.5 flex-grow w-full">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest">FILTER JURUSAN</label>
                    <div class="relative">
                        <select class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] outline-none transition-all bg-gray-50 cursor-pointer text-gray-600 appearance-none">
                            <option>Semua Jurusan</option>
                            <option>TKJ - Teknik Komputer Jaringan</option>
                            <option>RPL - Rekayasa Perangkat Lunak</option>
                            <option>MM - Multimedia</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-[20px]">expand_more</span>
                    </div>
                </div>
                <button class="bg-[#3F51B5] hover:bg-[#303F9F] text-white px-6 py-2 rounded-xl flex items-center justify-center gap-2 transition-all w-full md:w-auto text-sm font-bold shadow-sm active:scale-95 border-none cursor-pointer h-[38px]">
                    <span class="material-symbols-outlined text-[18px]">filter_list</span>
                    Terapkan Filter
                </button>
            </div>

            <!-- Row 2: Rentang Tanggal (Below Row 1) -->
            <div class="space-y-1.5 pt-3 border-t border-gray-50">
                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest">RENTANG TANGGAL</label>
                <div class="flex items-center gap-2 w-full md:max-w-md">
                    <div class="relative flex-1">
                        <input class="w-full pl-3 pr-2 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] outline-none transition-all bg-gray-50 text-gray-600" type="date" placeholder="dd/mm/yyyy"/>
                    </div>
                    <span class="text-gray-400 font-bold text-[10px] shrink-0 px-0.5">s/d</span>
                    <div class="relative flex-1">
                        <input class="w-full pl-3 pr-2 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] outline-none transition-all bg-gray-50 text-gray-600" type="date" placeholder="dd/mm/yyyy"/>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Metrics Card -->
        <div class="col-span-12 lg:col-span-4 bg-white rounded-2xl border border-gray-100 p-6 flex items-start justify-between shadow-sm hover:shadow-md transition-all relative overflow-hidden before:absolute before:top-0 before:left-0 before:right-0 before:h-[3px] before:bg-[#3F51B5]">
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Dipinjam</h3>
                <div class="text-3xl font-extrabold text-gray-800 mt-2">24</div>
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
                    <!-- Row 1 -->
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-[#3F51B5] text-xs">PJ-2023-0891</td>
                        <td class="px-6 py-4 font-bold text-gray-800 text-sm">Ahmad Fauzi</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-bold rounded border border-blue-100">TKJ</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 font-medium">MikroTik RB450Gx4</td>
                        <td class="px-6 py-4 text-xs text-gray-500 font-medium text-center">12 Okt 2023</td>
                        <td class="px-6 py-4 text-xs text-gray-500 font-medium text-center">15 Okt 2023</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-700 text-[10px] font-bold rounded-full border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Dipinjam
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-1">
                                <button class="p-1.5 text-gray-400 hover:text-[#3F51B5] hover:bg-indigo-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>
                                <button class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 2 -->
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-[#3F51B5] text-xs">PJ-2023-0892</td>
                        <td class="px-6 py-4 font-bold text-gray-800 text-sm">Siti Nurhaliza</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 bg-purple-50 text-purple-700 text-[10px] font-bold rounded border border-purple-100">RPL</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 font-medium">Laptop Dell Latitude 5400</td>
                        <td class="px-6 py-4 text-xs text-gray-500 font-medium text-center">11 Okt 2023</td>
                        <td class="px-6 py-4 text-xs text-gray-500 font-medium text-center">13 Okt 2023</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-[10px] font-bold rounded-full border border-green-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                Kembali
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-1">
                                <button class="p-1.5 text-gray-400 hover:text-[#3F51B5] hover:bg-indigo-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>
                                <button class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination & Info -->
        <div class="px-6 py-4 flex items-center justify-between bg-gray-50 border-t border-gray-100">
            <p class="text-xs text-gray-500 font-bold">Menampilkan 1-5 dari 142 data</p>
            <div class="flex items-center gap-1.5">
                <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:bg-white transition-colors cursor-pointer bg-white">
                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                </button>
                <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#3F51B5] text-white font-bold text-xs shadow-sm cursor-pointer border-none">1</button>
                <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-white transition-colors text-xs font-bold bg-white cursor-pointer">2</button>
                <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-white transition-colors text-xs font-bold bg-white cursor-pointer">3</button>
                <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:bg-white transition-colors cursor-pointer bg-white">
                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                </button>
            </div>
        </div>
    </div>
@endsection
