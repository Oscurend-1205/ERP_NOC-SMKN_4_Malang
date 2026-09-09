@extends('layouts.app')

@section('title', 'Dashboard ERP NOC')

@section('content')
<div class="space-y-6">

    <!-- BEGIN: Quick Action Bar -->
    <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4 bg-gradient-to-r from-slate-900 to-slate-800 p-4 sm:p-5 rounded-2xl shadow-lg">
        <div class="min-w-0">
            <div class="flex items-center gap-2">
                <h1 class="text-xl md:text-2xl font-extrabold text-white tracking-tight">Executive Dashboard</h1>
            </div>
            <p class="text-xs md:text-sm text-slate-300 mt-0.5">
                Monitoring menyeluruh infrastruktur laboratorium NOC.
            </p>
        </div>

        <!-- Quick Top Action Buttons (Single Line, No Wrap) -->
        <div class="flex items-center gap-2 overflow-x-auto shrink-0 pb-1 xl:pb-0">
            @if(in_array(Auth::user()->role, ['Superadmin', 'Admin']))
            <a href="{{ route('qr.admin') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm transition-all active:scale-95 whitespace-nowrap shrink-0">
                <span class="material-symbols-outlined text-[17px]">qr_code_scanner</span>
                <span>Panel QR Pinjam</span>
            </a>
            <a href="{{ route('stock-take.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-xl text-xs font-semibold transition-all active:scale-95 whitespace-nowrap shrink-0">
                <span class="material-symbols-outlined text-[17px]">fact_check</span>
                <span>Stok Opname</span>
            </a>
            @endif
            <a href="{{ route('items.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-white rounded-xl text-xs font-semibold transition-all whitespace-nowrap shrink-0">
                <span class="material-symbols-outlined text-[17px]">inventory_2</span>
                <span>Katalog Barang</span>
            </a>
            <a href="{{ route('procurements.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 rounded-xl text-xs font-semibold transition-all whitespace-nowrap shrink-0">
                <span class="material-symbols-outlined text-[17px]">shopping_cart_checkout</span>
                <span>Pengadaan</span>
                @if(isset($procurementStats) && $procurementStats['pending'] > 0)
                <span class="px-1.5 py-0.5 text-[10px] font-bold bg-amber-500 text-white rounded-full leading-none">{{ $procurementStats['pending'] }}</span>
                @endif
            </a>
        </div>
    </div>
    <!-- END: Quick Action Bar -->

    <!-- BEGIN: 5 KPI Metrics Strip (Compact & Balanced, No Long Sprawling Cards) -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        <!-- Metric 1: Total Aset -->
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-4 shadow-lg hover:shadow-xl transition-all flex flex-col justify-between relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-110 transition-transform"></div>
            <div class="flex items-center justify-between relative z-10">
                <span class="text-[11px] font-bold text-blue-100 uppercase tracking-wider">Total Aset</span>
                <div class="w-8 h-8 rounded-lg bg-white/20 text-white flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform backdrop-blur-sm">
                    <span class="material-symbols-outlined text-[18px]">devices</span>
                </div>
            </div>
            <div class="mt-3 relative z-10">
                <div class="text-2xl font-extrabold text-white tracking-tight">{{ $totalItems }}</div>
                <div class="flex items-center gap-1.5 text-[11px] text-blue-100 mt-1">
                    <span class="font-semibold">{{ $totalCategories }}</span> Kategori Aset
                </div>
            </div>
        </div>

        <!-- Metric 2: Tersedia -->
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-4 shadow-lg hover:shadow-xl transition-all flex flex-col justify-between relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-110 transition-transform"></div>
            <div class="flex items-center justify-between relative z-10">
                <span class="text-[11px] font-bold text-emerald-100 uppercase tracking-wider">Tersedia</span>
                <div class="w-8 h-8 rounded-lg bg-white/20 text-white flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform backdrop-blur-sm">
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                </div>
            </div>
            <div class="mt-3 relative z-10">
                <div class="text-2xl font-extrabold text-white tracking-tight">{{ $itemsTersedia ?? $itemsBaik }}</div>
                <div class="flex items-center gap-1.5 text-[11px] text-emerald-100 font-semibold mt-1">
                    Siap Digunakan
                </div>
            </div>
        </div>

        <!-- Metric 3: Dipinjam -->
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl p-4 shadow-lg hover:shadow-xl transition-all flex flex-col justify-between relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-110 transition-transform"></div>
            <div class="flex items-center justify-between relative z-10">
                <span class="text-[11px] font-bold text-amber-100 uppercase tracking-wider">Dipinjam</span>
                <div class="w-8 h-8 rounded-lg bg-white/20 text-white flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform backdrop-blur-sm">
                    <span class="material-symbols-outlined text-[18px]">assignment</span>
                </div>
            </div>
            <div class="mt-3 relative z-10">
                <div class="text-2xl font-extrabold text-white tracking-tight">{{ $itemsDipinjam ?? 0 }}</div>
                <div class="flex items-center gap-1.5 text-[11px] text-amber-100 font-semibold mt-1">
                    Praktik Siswa & Guru
                </div>
            </div>
        </div>

        <!-- Metric 4: Perlu Servis -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-4 shadow-lg hover:shadow-xl transition-all flex flex-col justify-between relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-110 transition-transform"></div>
            <div class="flex items-center justify-between relative z-10">
                <span class="text-[11px] font-bold text-red-100 uppercase tracking-wider">Perlu Servis</span>
                <div class="w-8 h-8 rounded-lg bg-white/20 text-white flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform backdrop-blur-sm">
                    <span class="material-symbols-outlined text-[18px]">build</span>
                </div>
            </div>
            <div class="mt-3 relative z-10">
                <div class="text-2xl font-extrabold text-white tracking-tight">{{ ($itemsMaintenance ?? 0) + ($itemsRusak ?? 0) }}</div>
                <div class="flex items-center gap-1.5 text-[11px] text-red-100 font-semibold mt-1">
                    Maintenance & Rusak
                </div>
            </div>
        </div>

        <!-- Metric 5: Nilai Valuasi -->
        <div class="col-span-2 md:col-span-1 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl p-4 shadow-lg hover:shadow-xl transition-all flex flex-col justify-between relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-110 transition-transform"></div>
            <div class="flex items-center justify-between relative z-10">
                <span class="text-[11px] font-bold text-indigo-100 uppercase tracking-wider">Nilai Aset</span>
                <div class="w-8 h-8 rounded-lg bg-white/20 text-white flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform backdrop-blur-sm">
                    <span class="material-symbols-outlined text-[18px]">account_balance_wallet</span>
                </div>
            </div>
            <div class="mt-3 relative z-10">
                <div class="text-lg md:text-xl font-extrabold text-white tracking-tight truncate" title="Rp {{ number_format($totalValue, 0, ',', '.') }}">
                    Rp {{ number_format($totalValue / 1000000, 1, ',', '.') }} Jt
                </div>
                <div class="text-[11px] text-indigo-100 mt-1 truncate">
                    Investasi Laboratorium
                </div>
            </div>
        </div>
    </div>
    <!-- END: 5 KPI Metrics Strip -->

    <!-- BEGIN: Main 2-Column Balanced Dashboard Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- ================================================================= -->
        <!-- LEFT COLUMN: Charts & Live Activities (8 Cols) -->
        <!-- ================================================================= -->
        <div class="lg:col-span-8 space-y-6">

            <!-- Card: Combined Activity Chart (Line + Area + Stacked Bar) -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-5 pb-4 border-b border-gray-100 gap-2">
                    <div>
                        <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-600 text-[18px]">analytics</span>
                            Analisis Pergerakan Barang
                        </h2>
                        <p class="text-xs text-gray-400 mt-0.5">Tren masuk, keluar, dan maintenance tahun {{ $currentYear }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($filterCategory || $filterLocation)
                        <span class="px-2.5 py-1 text-xs font-semibold bg-blue-50 text-blue-600 rounded-lg">
                            Filter Aktif
                        </span>
                        @endif
                        <span class="px-2.5 py-1 text-xs font-semibold bg-gray-100 text-gray-600 rounded-lg">
                            Tahun {{ $currentYear }}
                        </span>
                    </div>
                </div>
                <div class="pt-4 px-5">
                    <div id="combinedChart" class="w-full h-[280px]"></div>
                </div>

                <!-- Filter Section Inside Chart Card -->
                <div class="p-5 pt-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                    <form action="{{ route('dashboard') }}" method="GET" class="flex flex-wrap items-center gap-3">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-gray-400 text-[18px]">filter_list</span>
                            <span class="text-xs font-semibold text-gray-700">Filter Grafik:</span>
                        </div>

                        <!-- Year Filter -->
                        <div class="relative">
                            <select name="year" class="appearance-none bg-white border border-gray-200 text-gray-700 text-xs rounded-lg pl-3 pr-8 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none cursor-pointer hover:bg-gray-50 transition-colors">
                                @foreach($availableYears ?? [now()->year] as $year)
                                <option value="{{ $year }}" {{ $currentYear == $year ? 'selected' : '' }}>Tahun {{ $year }}</option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-[16px] pointer-events-none">expand_more</span>
                        </div>

                        <!-- Category Filter -->
                        <div class="relative">
                            <select name="category" class="appearance-none bg-white border border-gray-200 text-gray-700 text-xs rounded-lg pl-3 pr-8 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none cursor-pointer hover:bg-gray-50 transition-colors">
                                <option value="">Semua Kategori</option>
                                @foreach($itemsByCategory as $cat)
                                <option value="{{ $cat->id }}" {{ $filterCategory == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-[16px] pointer-events-none">expand_more</span>
                        </div>

                        <!-- Location Filter -->
                        <div class="relative">
                            <select name="location" class="appearance-none bg-white border border-gray-200 text-gray-700 text-xs rounded-lg pl-3 pr-8 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none cursor-pointer hover:bg-gray-50 transition-colors">
                                <option value="">Semua Lokasi</option>
                                @foreach($itemsByLocation as $loc)
                                <option value="{{ $loc->id }}" {{ $filterLocation == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-[16px] pointer-events-none">expand_more</span>
                        </div>

                        <!-- Reset Button -->
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 px-3 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800 hover:bg-white rounded-lg transition-colors border border-gray-200">
                            <span class="material-symbols-outlined text-[14px]">refresh</span>
                            <span>Reset</span>
                        </a>

                        <!-- Apply Button -->
                        <button type="submit" class="inline-flex items-center gap-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-[14px]">check</span>
                            <span>Terapkan</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Card: Aktivitas Mutasi Terkini (Clean High Density Table) -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-all">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="material-symbols-outlined text-indigo-600 text-[18px]">history</span>
                            Mutasi & Aktivitas Terkini
                        </h2>
                        <p class="text-xs text-gray-400 mt-0.5">Log pergerakan barang dan transaksi laboratorium</p>
                    </div>
                    <a href="{{ route('laporan.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors inline-flex items-center gap-1">
                        <span>Semua Log</span>
                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50/75 border-b border-gray-100">
                            <tr>
                                <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Waktu</th>
                                <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Perangkat</th>
                                <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Jenis Mutasi</th>
                                <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Keterangan / Lokasi</th>
                                <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-xs">
                            @forelse($recentMovements as $m)
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="py-3 px-4 text-gray-500 whitespace-nowrap">
                                    <div class="font-medium text-gray-700">{{ $m->created_at->format('d M Y') }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $m->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-bold text-gray-800 truncate max-w-[180px]" title="{{ $m->item->name ?? '-' }}">
                                        {{ $m->item->name ?? '-' }}
                                    </div>
                                    <div class="text-[10px] font-mono text-gray-400 mt-0.5">{{ $m->item->code ?? '-' }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    @php
                                        $typeClass = match($m->type) {
                                            'masuk' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'keluar' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            'pindah' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'maintenance' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            default => 'bg-gray-50 text-gray-700 border-gray-200'
                                        };
                                        $typeLabel = match($m->type) {
                                            'masuk' => 'Barang Masuk',
                                            'keluar' => 'Barang Keluar',
                                            'pindah' => 'Pindah Lokasi',
                                            'maintenance' => 'Maintenance',
                                            default => ucfirst($m->type)
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $typeClass }}">
                                        {{ $typeLabel }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-gray-600 max-w-[200px] truncate" title="{{ $m->notes ?: ($m->toLocation ? $m->toLocation->name : '-') }}">
                                    {{ $m->notes ?: ($m->toLocation ? $m->toLocation->name : '-') }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($m->item_id)
                                    <a href="{{ route('items.show', $m->item_id) }}" class="p-1 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg inline-flex items-center transition-colors" title="Lihat Detail Barang">
                                        <span class="material-symbols-outlined text-[16px]">visibility</span>
                                    </a>
                                    @else
                                    <span class="text-gray-300">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-400">
                                    <span class="material-symbols-outlined text-3xl mb-1 opacity-20 block">inbox</span>
                                    Belum ada aktivitas mutasi barang
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- ================================================================= -->
        <!-- RIGHT COLUMN: Compact ERP Widgets (4 Cols) - NO Long Cards! -->
        <!-- ================================================================= -->
        <div class="lg:col-span-4 space-y-6">

            <!-- Card: Pengadaan Alat Widget (Alert & Realtime Status) -->
            @if(isset($procurementStats) && ($procurementStats['pending'] > 0 || $procurementStats['in_procurement'] > 0))
            <div class="bg-gradient-to-br from-amber-600 to-orange-600 text-white rounded-2xl p-5 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider bg-white/20 text-white px-2.5 py-0.5 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-200 animate-ping"></span>
                        Pengadaan Alat
                    </span>
                    @if($procurementStats['pending'] > 0)
                    <span class="text-[11px] font-bold bg-white text-amber-700 px-2.5 py-0.5 rounded-full shadow-xs">
                        {{ $procurementStats['pending'] }} Pending
                    </span>
                    @endif
                </div>
                <h3 class="font-bold text-sm text-white leading-snug mt-1">
                    {{ $procurementStats['pending'] > 0 ? $procurementStats['pending'] . ' Pengajuan Butuh Verifikasi' : $procurementStats['in_procurement'] . ' Pengadaan Sedang Berjalan' }}
                </h3>
                <p class="text-xs text-amber-100 mt-1">
                    Estimasi: Rp {{ number_format($procurementStats['total_pending_cost'], 0, ',', '.') }}
                </p>

                <div class="mt-4 pt-3 border-t border-white/20 flex items-center justify-between gap-2">
                    <span class="text-[11px] text-amber-100">Review & Persetujuan</span>
                    <a href="{{ route('procurements.index', ['status' => 'pending']) }}" class="inline-flex items-center gap-1 text-xs font-bold text-amber-900 bg-white hover:bg-amber-50 px-3 py-1.5 rounded-lg transition-colors shadow-sm">
                        <span>Periksa</span>
                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </a>
                </div>
            </div>
            @endif

            <!-- Card: Sesi Stok Opname (Widget Ringkas Terpadu) -->
            @if(isset($activeStockTake) && $activeStockTake)
            <div class="bg-gradient-to-br from-[#1A1E35] to-[#2A3158] text-white rounded-2xl p-5 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between mb-3">
                    <span class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider bg-white/20 text-blue-200 px-2.5 py-0.5 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-ping"></span>
                        Stok Opname Aktif
                    </span>
                    <span class="text-xs font-mono font-bold text-gray-300">{{ $activeStockTake->code }}</span>
                </div>
                <h3 class="font-bold text-sm text-white leading-snug line-clamp-1" title="{{ $activeStockTake->title }}">
                    {{ $activeStockTake->title }}
                </h3>
                <p class="text-xs text-blue-200 mt-1 line-clamp-1">
                    {{ $activeStockTake->location ? $activeStockTake->location->name : 'Semua Ruangan' }}
                </p>

                <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between gap-2">
                    <span class="text-[11px] text-gray-300">Rekonsiliasi Fisik</span>
                    <a href="{{ route('stock-take.show', $activeStockTake->id) }}" class="inline-flex items-center gap-1 text-xs font-bold text-white bg-blue-600 hover:bg-blue-500 px-3 py-1.5 rounded-lg transition-colors">
                        <span>Cek Lembar</span>
                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </a>
                </div>
            </div>
            @else
            <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm flex items-center justify-between hover:shadow-md transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-[20px]">fact_check</span>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-gray-800">Sesi Stok Opname</div>
                        <div class="text-[11px] text-gray-400">Verifikasi fisik aset laboratorium</div>
                    </div>
                </div>
                <a href="{{ route('stock-take.create') }}" class="p-2 bg-gray-100 hover:bg-blue-50 hover:text-blue-600 text-gray-600 rounded-xl transition-colors inline-flex items-center" title="Mulai Sesi Baru">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                </a>
            </div>
            @endif

            <!-- Card: Distribusi Kondisi Aset (Donut Chart Compact) -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition-all">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-emerald-600 text-[16px]">pie_chart</span>
                        Kondisi Fisik Aset
                    </h3>
                    <span class="text-[11px] text-gray-400 font-medium">100% Terverifikasi</span>
                </div>

                <div class="pt-3 flex flex-col items-center">
                    <div id="donutChart" class="w-full flex justify-center"></div>

                    @php
                        $totalKondisi = array_sum($conditionStats) ?: 1;
                        $kondisiLabels = [
                            'baik' => 'Baik',
                            'rusak_ringan' => 'Rusak Ringan',
                            'rusak_berat' => 'Rusak Berat',
                            'hilang' => 'Hilang',
                        ];
                        $kondisiColors = [
                            'baik' => '#1A73E8',
                            'rusak_ringan' => '#F9A825',
                            'rusak_berat' => '#B85D19',
                            'hilang' => '#D32F2F',
                        ];
                    @endphp
                    <div class="grid grid-cols-2 gap-2 w-full mt-3 pt-3 border-t border-gray-100">
                        @foreach($conditionStats as $key => $val)
                        <div class="flex items-center justify-between p-2 rounded-lg bg-gray-50">
                            <div class="flex items-center gap-1.5 min-w-0">
                                <span class="w-2 h-2 rounded-full flex-shrink-0" style="background-color: {{ $kondisiColors[$key] }}"></span>
                                <span class="text-[11px] font-medium text-gray-600 truncate">{{ $kondisiLabels[$key] }}</span>
                            </div>
                            <span class="text-[11px] font-bold text-gray-800 ml-1">{{ $val }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Card: Notifikasi & Peringatan Cepat (Compact) -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition-all">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-amber-500 text-[16px]">notifications</span>
                        Pemberitahuan Sistem
                    </h3>
                    <a href="{{ route('notifications.index') }}" class="text-[11px] font-bold text-blue-600 hover:text-blue-800 transition-colors">
                        Lihat Semua
                    </a>
                </div>

                <div class="divide-y divide-gray-50 pt-1">
                    @if(isset($recentNotifications) && $recentNotifications->count() > 0)
                        @foreach($recentNotifications->take(3) as $notif)
                        <a href="{{ $notif->action_url ?: route('notifications.index') }}" class="py-2.5 flex items-start gap-3 hover:bg-gray-50 rounded-lg px-1 transition-colors block group">
                            <div class="w-7 h-7 rounded-lg {{ $notif->type === 'danger' ? 'bg-red-100 text-red-600' : ($notif->type === 'warning' ? 'bg-amber-100 text-amber-600' : ($notif->type === 'success' ? 'bg-emerald-100 text-emerald-600' : 'bg-blue-100 text-blue-600')) }} flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-[15px]">{{ $notif->icon ?: 'notifications' }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1">
                                    <h4 class="text-xs font-semibold text-gray-800 truncate group-hover:text-blue-600 transition-colors">{{ $notif->title }}</h4>
                                    <span class="text-[10px] text-gray-400 flex-shrink-0">{{ $notif->time_ago }}</span>
                                </div>
                                <p class="text-[11px] text-gray-500 line-clamp-1 mt-0.5">{{ $notif->message }}</p>
                            </div>
                        </a>
                        @endforeach
                    @else
                        <div class="py-6 text-center text-gray-400 text-xs">
                            <span class="material-symbols-outlined text-2xl mb-1 text-gray-300 block">notifications_none</span>
                            Tidak ada notifikasi baru
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
    <!-- END: Main 2-Column Balanced Dashboard Layout -->

</div>

<!-- ApexCharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
(function() {
    // Chart Data from Database
    const monthlyIncomingData = {!! json_encode($monthlyData ?? []) !!};
    const monthlyOutgoingData = {!! json_encode($monthlyOutgoingData ?? []) !!};
    const monthlyMaintenanceData = {!! json_encode($monthlyMaintenanceData ?? []) !!};
    const conditionStatsData = {!! json_encode($conditionStats) !!};
    const monthLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

    const kondisiLabels = { baik: 'Baik', rusak_ringan: 'Rusak Ringan', rusak_berat: 'Rusak Berat', hilang: 'Hilang' };
    const kondisiColors = { baik: '#3B82F6', rusak_ringan: '#F59E0B', rusak_berat: '#EF4444', hilang: '#6B7280' };

    function initDashboardCharts() {
        // Combined Chart - Line + Area + Stacked Bar
        var combinedOptions = {
            series: [
                {
                    name: 'Barang Masuk',
                    type: 'area',
                    data: monthlyIncomingData
                },
                {
                    name: 'Barang Keluar',
                    type: 'bar',
                    data: monthlyOutgoingData
                },
                {
                    name: 'Maintenance',
                    type: 'line',
                    data: monthlyMaintenanceData
                }
            ],
            chart: {
                height: 280,
                type: 'line',
                stacked: false,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
                zoom: { enabled: false }
            },
            colors: ['#3B82F6', '#10B981', '#F59E0B'],
            stroke: {
                width: [2, 0, 3],
                curve: 'smooth',
                dashArray: [0, 0, 5]
            },
            plotOptions: {
                bar: {
                    columnWidth: '40%',
                    borderRadius: 4,
                }
            },
            fill: {
                opacity: [0.3, 1, 1],
                type: ['solid', 'solid', 'solid']
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: monthLabels,
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: { colors: '#9CA3AF', fontSize: '11px', fontFamily: 'Inter, sans-serif' }
                }
            },
            yaxis: {
                labels: {
                    style: { colors: '#9CA3AF', fontSize: '11px', fontFamily: 'Inter, sans-serif' },
                    formatter: function(val) { return Math.round(val); }
                }
            },
            grid: {
                borderColor: '#F3F4F6',
                strokeDashArray: 4,
                xaxis: { lines: { show: false } },
                yaxis: { lines: { show: true } },
                padding: { top: 0, right: 10, bottom: 0, left: 10 }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                fontSize: '11px',
                fontFamily: 'Inter, sans-serif',
                markers: { radius: 12 }
            },
            tooltip: {
                enabled: true,
                theme: 'light',
                shared: true,
                intersect: false,
                y: {
                    formatter: function(val, { seriesIndex, dataPointIndex, w }) {
                        const seriesName = w.config.series[seriesIndex].name;
                        return val + " " + (seriesName === 'Maintenance' ? 'Unit' : 'Unit');
                    }
                }
            }
        };

        if (document.querySelector("#combinedChart")) {
            document.querySelector("#combinedChart").innerHTML = "";
            var combinedChart = new ApexCharts(document.querySelector("#combinedChart"), combinedOptions);
            combinedChart.render();
        }

        // Donut Chart - Distribusi Kondisi Barang
        var donutLabels = [];
        var donutSeries = [];
        var donutColors = [];

        for (var key in conditionStatsData) {
            donutLabels.push(kondisiLabels[key] || key);
            donutSeries.push(conditionStatsData[key] || 0);
            donutColors.push(kondisiColors[key] || '#9E9E9E');
        }

        var donutOptions = {
            series: donutSeries,
            labels: donutLabels,
            colors: donutColors,
            chart: {
                type: 'donut',
                height: 180,
                fontFamily: 'Inter, sans-serif'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '72%',
                        labels: {
                            show: true,
                            name: { show: false },
                            value: {
                                show: true,
                                fontSize: '20px',
                                fontWeight: 800,
                                color: '#111827',
                                formatter: function(val) { return val + ' Unit'; }
                            },
                            total: {
                                show: true,
                                label: 'Total',
                                fontSize: '11px',
                                color: '#6B7280',
                                formatter: function(w) {
                                    return w.globals.seriesTotals.reduce(function(a, b) { return a + b; }, 0) + ' Unit';
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
            legend: { show: false },
            stroke: { width: 2, colors: ['#FFFFFF'] },
            tooltip: {
                enabled: true,
                y: { formatter: function(val) { return val + " Unit"; } }
            }
        };

        if (document.querySelector("#donutChart")) {
            document.querySelector("#donutChart").innerHTML = "";
            var donutChart = new ApexCharts(document.querySelector("#donutChart"), donutOptions);
            donutChart.render();
        }
    }

    // Initialize immediately & hook to events
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDashboardCharts);
    } else {
        initDashboardCharts();
    }
    document.addEventListener('turbo:load', initDashboardCharts);
})();
</script>
@endsection
