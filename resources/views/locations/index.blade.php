@extends('layouts.app')

@section('title', 'Manajemen Ruangan')

@section('content')
<div class="space-y-6">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-50 text-green-700 p-4 rounded-xl flex items-center gap-3 border border-green-200">
            <span class="material-symbols-outlined text-[20px]">check_circle</span>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 text-red-700 p-4 rounded-xl flex items-center gap-3 border border-red-200">
            <span class="material-symbols-outlined text-[20px]">error</span>
            <span class="font-medium text-sm">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Header Utama --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-xs relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-purple-600 via-violet-500 to-purple-700"></div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-purple-50 border border-purple-100 text-purple-700 text-[11px] font-mono font-semibold uppercase tracking-wider mb-2">
                    <span>DATA MASTER</span>
                    <span class="w-1 h-1 rounded-full bg-purple-400"></span>
                    <span>MANAJEMEN RUANGAN</span>
                </div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Manajemen Ruangan</h2>
                <p class="text-xs md:text-sm text-gray-500 mt-1">Kelola data ruangan untuk lokasi penyimpanan barang inventaris.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                @if(auth()->user()->role === 'Superadmin')
                <button onclick="document.getElementById('addLocationModal').classList.remove('hidden')" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-[#3F51B5] hover:bg-[#3949AB] text-white font-bold rounded-xl transition-all shadow-sm text-xs cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    <span>+ Tambah Ruangan</span>
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats Bento Grid -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 md:col-span-4 bg-white p-4 rounded-xl border border-gray-100 shadow-sm relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Total Ruangan</p>
                <h2 class="text-2xl font-black text-gray-900">{{ $locations->total() }}</h2>
            </div>
            <span class="material-symbols-outlined absolute -right-2 -bottom-2 text-6xl text-purple-500 opacity-5 group-hover:opacity-10 transition-opacity">meeting_room</span>
        </div>
        <div class="col-span-12 md:col-span-4 bg-white p-4 rounded-xl border border-gray-100 shadow-sm relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Pengelola</p>
                <h2 class="text-2xl font-black text-gray-900">12</h2>
            </div>
            <span class="material-symbols-outlined absolute -right-2 -bottom-2 text-6xl text-blue-500 opacity-5 group-hover:opacity-10 transition-opacity">groups</span>
        </div>
        <div class="col-span-12 md:col-span-4 bg-white p-4 rounded-xl border border-gray-100 shadow-sm relative overflow-hidden group border-b-4 border-b-red-500">
            <div class="relative z-10">
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Kapasitas</p>
                <h2 class="text-2xl font-black text-gray-900">86%</h2>
            </div>
            <span class="material-symbols-outlined absolute -right-2 -bottom-2 text-6xl text-red-500 opacity-5 group-hover:opacity-10 transition-opacity">warning</span>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-600 text-[10px] uppercase font-bold tracking-wider border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3">NO</th>
                        <th class="px-4 py-3">KODE RUANGAN</th>
                        <th class="px-4 py-3">NAMA RUANGAN</th>
                        <th class="px-4 py-3">PENANGGUNG JAWAB</th>
                        <th class="px-4 py-3">BARANG</th>
                        <th class="px-4 py-3 text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-[11px]">
                    @forelse($locations as $index => $location)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-4 py-3 text-gray-500">{{ $locations->firstItem() + $index }}</td>
                        <td class="px-4 py-3">
                            <span class="font-black text-blue-600 tracking-tight">{{ $location->code }}</span>
                        </td>
                        <td class="px-4 py-3 font-bold text-gray-900">{{ $location->name }}</td>
                        <td class="px-4 py-3 text-gray-500">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-[10px] font-bold text-blue-600">AD</div>
                                <span>Administrator</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-[9px] font-bold uppercase">{{ $location->items_count }} UNIT</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button class="p-1 text-blue-600 hover:bg-blue-50 rounded transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                                <button class="p-1 text-red-600 hover:bg-red-50 rounded transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500 italic text-xs">Belum ada data ruangan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-4 py-3 bg-white border-t border-gray-50 flex items-center justify-between">
            <p class="text-[10px] text-gray-500 italic">Menampilkan {{ $locations->firstItem() ?? 0 }} hingga {{ $locations->lastItem() ?? 0 }} entri</p>
            <div class="flex items-center gap-1">
                {{ $locations->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('modals')
    @if(auth()->user()->role === 'Superadmin')
    <!-- Add Location Modal -->
    <div id="addLocationModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">Tambah Ruangan Baru</h3>
                <button onclick="document.getElementById('addLocationModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form action="{{ route('locations.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kode Ruangan</label>
                    <input type="text" name="code" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Ruangan</label>
                    <input type="text" name="name" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none" required>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('addLocationModal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition-colors">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif
@endpush
