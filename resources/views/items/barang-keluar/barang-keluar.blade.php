<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Barang Keluar - ERP NOC</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #F1F5F9; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }
    </style>
    <style>
        html { zoom: 0.9; }
        .min-h-screen { min-height: calc(100vh / 0.9) !important; }
        .h-screen { height: calc(100vh / 0.9) !important; }
        table thead { background-color: #e5e7eb !important; border-bottom: 1px solid #d1d5db !important; }
        table thead th { color: #1f2937 !important; font-size: 0.75rem !important; font-weight: 700 !important; text-transform: uppercase !important; letter-spacing: 0.05em !important; padding: 0.75rem 1rem !important; }
        table tbody td { padding: 0.5rem 1rem !important; }
    </style>
</head>
<body class="flex h-screen overflow-hidden bg-[#F8FAFC]">

    @include('partials.sidebar')

    <main class="flex-grow flex flex-col h-screen overflow-y-auto">
        @include('partials.topbar')

        <div id="pjax-content" class="p-4 md:p-10 pt-4 md:pt-6 space-y-6">

            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Data Barang Keluar</h2>
                    <p class="text-sm text-gray-500 mt-1">Riwayat peminjaman & pengeluaran barang aset NOC</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('items.index') }}" class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-600 font-semibold rounded-lg hover:bg-gray-200 transition-all text-sm border border-gray-200">
                        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                        Kembali
                    </a>
                    {{-- Export Dropdown --}}
                    <div class="relative">
                        <button onclick="document.getElementById('exportMenu').classList.toggle('hidden')" class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-all shadow-sm active:scale-95 text-sm">
                            <span class="material-symbols-outlined text-[18px]">download</span>
                            Export
                        </button>
                        <div id="exportMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden">
                            <a href="{{ route('export.barang-keluar.csv') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <span class="material-symbols-outlined text-[18px] text-green-600">table_chart</span>
                                Excel (CSV)
                            </a>
                            <a href="{{ route('export.barang-keluar.print') }}" target="_blank" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <span class="material-symbols-outlined text-[18px] text-red-600">picture_as_pdf</span>
                                PDF (Cetak)
                            </a>
                        </div>
                    </div>
                    <a href="{{ route('qr.admin') }}" class="flex items-center gap-2 px-4 py-2 bg-[#3F51B5] text-white font-semibold rounded-lg hover:bg-[#3949AB] transition-all shadow-sm active:scale-95 text-sm">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        Catat Peminjaman
                    </a>
                </div>
            </div>

            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-500">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-red-500">error</span>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Filter Bar --}}
            <form method="GET" action="{{ route('items.barang-keluar') }}" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-wrap items-center gap-3">
                <div class="relative">
                    <select name="date_range" onchange="this.form.submit()" class="appearance-none pl-4 pr-9 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-[#3F51B5] outline-none cursor-pointer bg-white text-gray-700">
                        <option value="">Rentang Tanggal</option>
                        <option value="today" {{ request('date_range') === 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="week" {{ request('date_range') === 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ request('date_range') === 'month' ? 'selected' : '' }}>Bulan Ini</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
                <div class="relative">
                    <select name="status" onchange="this.form.submit()" class="appearance-none pl-4 pr-9 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-[#3F51B5] outline-none cursor-pointer bg-white text-gray-700">
                        <option value="">Semua Status</option>
                        <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
                <div class="relative ml-auto flex items-center gap-2">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <span class="material-symbols-outlined text-[18px]">search</span>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari peminjam atau barang..." class="pl-9 pr-4 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-[#3F51B5] outline-none w-64 text-gray-700 placeholder-gray-400" />
                    </div>
                    <button type="submit" class="px-3 py-2 bg-[#3F51B5] text-white rounded-xl text-sm hover:bg-[#3949AB] transition-all">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                    </button>
                </div>
            </form>

            {{-- Table Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider w-12 text-center">No</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">ID Pinjam</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Peminjam</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Tgl Pinjam</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Tgl Kembali</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Status</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Kondisi Kembali</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($peminjamans as $index => $p)
                                @php $item = $p->item; @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-6 text-sm text-gray-500 text-center">{{ $peminjamans->firstItem() + $index }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600 font-mono font-bold text-[#3F51B5]">PJ-{{ str_pad($p->id_pinjam, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td class="py-4 px-6 font-semibold text-sm text-gray-800">{{ $p->nama_peminjam }}
                                        @if($p->kelas)
                                            <span class="block text-[10px] text-gray-400 font-normal">{{ $p->kelas }}</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-700">
                                        {{ $item->name ?? '-' }}
                                        <span class="block text-[10px] text-gray-400 font-mono">{{ $p->item_code }}</span>
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $item->category->name ?? '-' }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $p->waktu_pinjam ? $p->waktu_pinjam->format('d M Y') : '-' }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $p->waktu_kembali ? $p->waktu_kembali->format('d M Y') : '-' }}</td>
                                    <td class="py-4 px-6 text-center">
                                        @if($p->status == 'dipinjam')
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
                                    <td class="py-4 px-6 text-center">
                                        @if($p->kondisi_saat_kembali)
                                            @php
                                                $kClass = match($p->kondisi_saat_kembali) {
                                                    'baik' => 'bg-green-100 text-green-700',
                                                    'rusak_ringan' => 'bg-yellow-100 text-yellow-700',
                                                    'rusak_berat' => 'bg-red-100 text-red-700',
                                                    'hilang' => 'bg-gray-100 text-gray-700',
                                                    default => 'bg-gray-100 text-gray-700',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold {{ $kClass }}">
                                                {{ ucfirst(str_replace('_', ' ', $p->kondisi_saat_kembali)) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <span class="material-symbols-outlined text-5xl text-gray-300 mb-3">inventory_2</span>
                                            <p class="text-gray-500 text-sm">Belum ada data barang keluar.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($peminjamans->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $peminjamans->links() }}
                    </div>
                @endif
            </div>

        </div>
    </main>

    <script>
        // Close export dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('exportMenu');
            if (menu && !menu.classList.contains('hidden') && !e.target.closest('.relative')) {
                menu.classList.add('hidden');
            }
        });
    </script>

    @vite(['resources/js/turbo-navigation.js'])
    @include('components.accessibility-button')
</body>
</html>
