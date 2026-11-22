@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<!-- Page Title Area -->
<div class="flex flex-col md:flex-row md:items-start justify-between mb-4 gap-3">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Hub Laporan Utama</h1>
        <p class="text-sm text-slate-500 mt-0.5">Ringkasan aktivitas inventaris dan aliran barang.</p>
    </div>
    <div class="relative flex items-center gap-2">
        <!-- Settings Button -->
        <a href="{{ route('laporan.settings') }}" class="flex items-center gap-2 px-3.5 py-1.5 bg-slate-100 text-slate-700 rounded-lg shadow-sm hover:bg-slate-200 transition-colors font-medium text-sm">
            <i data-lucide="settings" class="w-4 h-4"></i>
            Pengaturan
        </a>
        
        <!-- Export Dropdown -->
        <div class="relative">
            <button type="button" onclick="document.getElementById('exportMenu').classList.toggle('hidden')" class="flex items-center gap-2 px-3.5 py-1.5 bg-green-600 text-white rounded-lg shadow-sm hover:bg-green-700 transition-colors font-medium text-sm">
                <span class="material-symbols-outlined text-[18px]">download</span>
                Export Laporan
                <span class="material-symbols-outlined text-[16px]">expand_more</span>
            </button>
            <div id="exportMenu" class="hidden absolute right-0 mt-1 w-72 bg-white rounded-xl shadow-xl border border-slate-100 z-30 overflow-hidden">
                {{-- Settings --}}
                <a href="{{ route('laporan.settings') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition-colors border-b border-slate-100">
                    <span class="material-symbols-outlined text-[20px] text-slate-600">settings</span>
                    <div>
                        <div class="font-semibold">Pengaturan Laporan</div>
                        <div class="text-[11px] text-slate-400">Customisasi judul, tanda tangan, dan info sekolah</div>
                    </div>
                </a>
                
                {{-- Summary PDF --}}
                <a href="{{ route('export.ringkasan.print') }}" target="_blank" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 hover:bg-blue-50 transition-colors border-b border-slate-100">
                    <span class="material-symbols-outlined text-[20px] text-blue-600">summarize</span>
                    <div>
                        <div class="font-semibold">Ringkasan Aktivitas (PDF)</div>
                        <div class="text-[11px] text-slate-400">Statistik dashboard & ringkasan kondisi</div>
                    </div>
                </a>
                {{-- Excel Multi-Sheet --}}
                <a href="{{ route('export.laporan-lengkap.excel') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 hover:bg-green-50 transition-colors border-b border-slate-100">
                    <span class="material-symbols-outlined text-[20px] text-green-600">table_chart</span>
                    <div>
                        <div class="font-semibold">Laporan Lengkap (Excel)</div>
                        <div class="text-[11px] text-slate-400">5 sheet: Ringkasan, Inventaris, Peminjaman, Masuk, Keluar</div>
                    </div>
                </a>
                {{-- Inventaris --}}
                <div class="px-4 py-2 text-[10px] font-bold text-slate-400 uppercase bg-slate-50">Data Inventaris</div>
                <a href="{{ route('export.inventaris.csv') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                    <span class="material-symbols-outlined text-[18px] text-green-600">grid_on</span>
                    Daftar Inventaris (CSV)
                </a>
                <a href="{{ route('export.inventaris.print') }}" target="_blank" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                    <span class="material-symbols-outlined text-[18px] text-red-600">picture_as_pdf</span>
                    Daftar Inventaris (Print/PDF)
                </a>
                {{-- Peminjaman --}}
                <div class="px-4 py-2 text-[10px] font-bold text-slate-400 uppercase bg-slate-50">Log Peminjaman</div>
                <a href="{{ route('export.peminjaman.csv', ['date_range' => $dateRange]) }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                    <span class="material-symbols-outlined text-[18px] text-green-600">grid_on</span>
                    Peminjaman (CSV)
                </a>
                <a href="{{ route('export.peminjaman.print', ['date_range' => $dateRange]) }}" target="_blank" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                    <span class="material-symbols-outlined text-[18px] text-red-600">picture_as_pdf</span>
                    Peminjaman (Print/PDF)
                </a>
                {{-- Barang Masuk --}}
                <div class="px-4 py-2 text-[10px] font-bold text-slate-400 uppercase bg-slate-50">Aliran Barang</div>
                <a href="{{ route('export.barang-masuk.csv', ['date_range' => $dateRange]) }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                    <span class="material-symbols-outlined text-[18px] text-green-600">grid_on</span>
                    Barang Masuk (CSV)
                </a>
                <a href="{{ route('export.barang-masuk.print', ['date_range' => $dateRange]) }}" target="_blank" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                    <span class="material-symbols-outlined text-[18px] text-red-600">picture_as_pdf</span>
                    Barang Masuk (Print/PDF)
                </a>
                {{-- Barang Keluar --}}
                <a href="{{ route('export.barang-keluar.csv', ['date_range' => $dateRange]) }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                    <span class="material-symbols-outlined text-[18px] text-green-600">grid_on</span>
                    Barang Keluar (CSV)
                </a>
                <a href="{{ route('export.barang-keluar.print', ['date_range' => $dateRange]) }}" target="_blank" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                    <span class="material-symbols-outlined text-[18px] text-red-600">picture_as_pdf</span>
                    Barang Keluar (Print/PDF)
                </a>
            </div>
        </div>

        <!-- Filter Periode Dinamis -->
        <div class="relative">
            <button type="button" onclick="document.getElementById('periodMenu').classList.toggle('hidden')" class="flex items-center gap-2 px-3.5 py-1.5 bg-white border border-slate-200 rounded-lg shadow-sm hover:bg-slate-50 transition-colors text-slate-700 font-medium text-sm">
                <i data-lucide="calendar" class="w-4 h-4 text-slate-500"></i>
                <span>{{ $rangeLabel }}</span>
                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-500"></i>
            </button>
            <div id="periodMenu" class="hidden absolute right-0 mt-1 w-56 bg-white rounded-xl shadow-xl border border-slate-100 z-30 overflow-hidden py-1">
                <div class="px-3 py-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider bg-slate-50">Pilih Rentang Waktu</div>
                <a href="{{ route('laporan.index', ['date_range' => 'all']) }}" class="flex items-center justify-between px-3.5 py-2 text-xs font-medium {{ $dateRange === 'all' ? 'text-blue-600 bg-blue-50/60 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                    <span>Semua Waktu</span>
                    @if($dateRange === 'all') <i data-lucide="check" class="w-3.5 h-3.5 text-blue-600"></i> @endif
                </a>
                <a href="{{ route('laporan.index', ['date_range' => 'month']) }}" class="flex items-center justify-between px-3.5 py-2 text-xs font-medium {{ $dateRange === 'month' ? 'text-blue-600 bg-blue-50/60 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                    <span>Bulan Ini ({{ now()->translatedFormat('M Y') }})</span>
                    @if($dateRange === 'month') <i data-lucide="check" class="w-3.5 h-3.5 text-blue-600"></i> @endif
                </a>
                <a href="{{ route('laporan.index', ['date_range' => 'last_month']) }}" class="flex items-center justify-between px-3.5 py-2 text-xs font-medium {{ $dateRange === 'last_month' ? 'text-blue-600 bg-blue-50/60 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                    <span>Bulan Lalu ({{ now()->subMonth()->translatedFormat('M Y') }})</span>
                    @if($dateRange === 'last_month') <i data-lucide="check" class="w-3.5 h-3.5 text-blue-600"></i> @endif
                </a>
                <a href="{{ route('laporan.index', ['date_range' => 'week']) }}" class="flex items-center justify-between px-3.5 py-2 text-xs font-medium {{ $dateRange === 'week' ? 'text-blue-600 bg-blue-50/60 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                    <span>Minggu Ini</span>
                    @if($dateRange === 'week') <i data-lucide="check" class="w-3.5 h-3.5 text-blue-600"></i> @endif
                </a>
                <a href="{{ route('laporan.index', ['date_range' => 'today']) }}" class="flex items-center justify-between px-3.5 py-2 text-xs font-medium {{ $dateRange === 'today' ? 'text-blue-600 bg-blue-50/60 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                    <span>Hari Ini</span>
                    @if($dateRange === 'today') <i data-lucide="check" class="w-3.5 h-3.5 text-blue-600"></i> @endif
                </a>
                <a href="{{ route('laporan.index', ['date_range' => 'year']) }}" class="flex items-center justify-between px-3.5 py-2 text-xs font-medium {{ $dateRange === 'year' ? 'text-blue-600 bg-blue-50/60 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                    <span>Tahun Ini ({{ now()->year }})</span>
                    @if($dateRange === 'year') <i data-lucide="check" class="w-3.5 h-3.5 text-blue-600"></i> @endif
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards Row -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
    <!-- Card 1: Barang Masuk -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex flex-col relative overflow-hidden group hover:-translate-y-0.5 transition-all duration-300">
        <div class="absolute bottom-0 left-0 w-full h-1 bg-transparent group-hover:bg-blue-500 transition-colors"></div>
        <div class="flex justify-between items-start mb-2.5">
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">BARANG MASUK</h3>
            <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                <span class="material-symbols-outlined text-[18px]">move_down</span>
            </div>
        </div>
        <div class="mb-3">
            <div class="text-2xl font-bold text-slate-900 mb-0.5">{{ number_format($barangMasuk) }}</div>
            <div class="flex items-center gap-1 {{ $masukTrend >= 0 ? 'text-green-600' : 'text-red-500' }} text-xs font-medium">
                <span class="material-symbols-outlined text-[14px]">{{ $masukTrend >= 0 ? 'trending_up' : 'trending_down' }}</span>
                {{ $masukTrend >= 0 ? '+' : '' }}{{ $masukTrend }}% vs bulan lalu
            </div>
        </div>
        <div class="space-y-1.5 mb-3 flex-grow">
            <div class="flex justify-between border-b border-slate-100 pb-1.5">
                <span class="text-slate-500 text-xs">Total Nilai</span>
                <span class="text-slate-900 text-xs font-semibold">Rp {{ number_format($nilaiMasuk, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-100 pb-1.5">
                <span class="text-slate-500 text-xs">Bulan Ini</span>
                <span class="text-slate-900 text-xs font-semibold">{{ $barangMasukBulanIni }} catatan</span>
            </div>
        </div>
        <a href="{{ route('export.barang-masuk.csv', ['date_range' => $dateRange]) }}" class="w-full py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs font-medium rounded-lg transition-colors flex items-center justify-center gap-1.5">
            Export CSV
            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
        </a>
    </div>

    <!-- Card 2: Barang Keluar -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex flex-col relative overflow-hidden group hover:-translate-y-0.5 transition-all duration-300">
        <div class="absolute bottom-0 left-0 w-full h-1 bg-transparent group-hover:bg-orange-500 transition-colors"></div>
        <div class="flex justify-between items-start mb-2.5">
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">BARANG KELUAR</h3>
            <div class="w-9 h-9 rounded-lg bg-orange-50 flex items-center justify-center text-orange-600">
                <span class="material-symbols-outlined text-[18px]">move_up</span>
            </div>
        </div>
        <div class="mb-3">
            <div class="text-2xl font-bold text-slate-900 mb-0.5">{{ number_format($barangKeluar) }}</div>
            <div class="flex items-center gap-1 {{ $keluarTrend >= 0 ? 'text-green-600' : 'text-red-500' }} text-xs font-medium">
                <span class="material-symbols-outlined text-[14px]">{{ $keluarTrend >= 0 ? 'trending_up' : 'trending_down' }}</span>
                {{ $keluarTrend >= 0 ? '+' : '' }}{{ $keluarTrend }}% vs bulan lalu
            </div>
        </div>
        <div class="space-y-1.5 mb-3 flex-grow">
            <div class="flex justify-between border-b border-slate-100 pb-1.5">
                <span class="text-slate-500 text-xs">Total Aset</span>
                <span class="text-slate-900 text-xs font-semibold">{{ number_format($totalAset) }} unit</span>
            </div>
            <div class="flex justify-between border-b border-slate-100 pb-1.5">
                <span class="text-slate-500 text-xs">Bulan Ini</span>
                <span class="text-slate-900 text-xs font-semibold">{{ $barangKeluarBulanIni }} catatan</span>
            </div>
        </div>
        <a href="{{ route('export.barang-keluar.csv', ['date_range' => $dateRange]) }}" class="w-full py-1.5 bg-orange-50 hover:bg-orange-100 text-orange-600 text-xs font-medium rounded-lg transition-colors flex items-center justify-center gap-1.5">
            Export CSV
            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
        </a>
    </div>

    <!-- Card 3: Peminjaman -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex flex-col relative overflow-hidden group hover:-translate-y-0.5 transition-all duration-300">
        <div class="absolute bottom-0 left-0 w-full h-1 bg-transparent group-hover:bg-indigo-500 transition-colors"></div>
        <div class="flex justify-between items-start mb-2.5">
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">PEMINJAMAN</h3>
            <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                <span class="material-symbols-outlined text-[18px]">handshake</span>
            </div>
        </div>
        <div class="mb-3">
            <div class="text-2xl font-bold text-slate-900 mb-0.5">{{ number_format($peminjamanAktif) }}</div>
            <div class="flex items-center gap-1 text-red-500 text-xs font-medium">
                <span class="material-symbols-outlined text-[14px]">warning</span>
                {{ $peminjamanTerlambat }} melewati batas waktu
            </div>
        </div>
        <div class="space-y-1.5 mb-3 flex-grow">
            <div class="flex justify-between border-b border-slate-100 pb-1.5">
                <span class="text-slate-500 text-xs">Peminjam Unik</span>
                <span class="text-slate-900 text-xs font-semibold">{{ $totalPeminjam }} orang</span>
            </div>
            <div class="flex justify-between border-b border-slate-100 pb-1.5">
                <span class="text-slate-500 text-xs">Tingkat Pengembalian</span>
                <span class="text-slate-900 text-xs font-semibold">{{ $tingkatPengembalian }}%</span>
            </div>
        </div>
        <a href="{{ route('export.peminjaman.csv', ['date_range' => $dateRange]) }}" class="w-full py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-xs font-medium rounded-lg transition-colors flex items-center justify-center gap-1.5">
            Export CSV
            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
        </a>
    </div>
</div>

<!-- Bottom Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <!-- Chart Area -->
    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex flex-col">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Volume Transaksi (30 Hari Terakhir)</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">
                    Rata-rata: <span class="font-semibold text-slate-600">{{ $rataRata30Hari }} transaksi/hari</span>
                    &nbsp;·&nbsp; Hari ini: <span class="font-semibold text-blue-600">{{ $todayVolume }} transaksi</span>
                </p>
            </div>
            <a href="{{ Auth::user()->role === 'Superadmin' ? route('activity-log.index') : route('peminjaman.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors p-1" title="Lihat Riwayat Transaksi">
                <i data-lucide="external-link" class="w-4 h-4"></i>
            </a>
        </div>
        
        <!-- Dynamic Chart -->
        <div class="flex-1 relative min-h-[200px] flex items-end gap-0.5 pt-5">
            @foreach($chartItems as $idx => $item)
                @php
                    $tinggiPersen = $maxVolume > 0 ? max(3, ($item['total'] / $maxVolume) * 100) : 3;
                    $isToday = $item['is_today'];
                    $isAboveAvg = $rataRata30Hari > 0 && $item['total'] >= $rataRata30Hari;
                    if ($isToday) {
                        $barClass = 'bg-blue-500 hover:bg-blue-600';
                    } elseif ($isAboveAvg && $item['total'] > 0) {
                        $barClass = 'bg-blue-100 border-t-2 border-blue-500 hover:bg-blue-200';
                    } elseif ($item['total'] === 0) {
                        $barClass = 'bg-slate-50 hover:bg-slate-100';
                    } else {
                        $barClass = 'bg-slate-100 hover:bg-slate-200';
                    }
                @endphp
                <div class="w-full {{ $barClass }} rounded-t-sm relative group transition-colors overflow-hidden"
                     style="height: {{ $tinggiPersen }}%;"
                     title="{{ $item['full_date'] }}: {{ $item['total'] }} transaksi ({{ $item['peminjaman'] }} peminjaman, {{ $item['movement'] }} pergerakan)">
                    <div class="hidden group-hover:block absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] px-2 py-1 rounded z-20 whitespace-nowrap shadow-lg text-center pointer-events-none">
                        <div class="font-bold">{{ $item['full_date'] }}</div>
                        <div>{{ $item['total'] }} trans</div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- X Axis Labels Dynamic -->
        <div class="flex justify-between text-[10px] text-slate-400 mt-2 px-1">
            @foreach($chartItems as $item)
                @if(!empty($item['axis_label']))
                    <span class="font-medium text-slate-500">{{ $item['axis_label'] }}</span>
                @endif
            @endforeach
        </div>

        <!-- Legend -->
        <div class="flex items-center gap-4 mt-3 pt-3 border-t border-slate-50 text-[10px] text-slate-500">
            <div class="flex items-center gap-1.5"><div class="w-3 h-3 rounded-sm bg-blue-500"></div>Hari ini</div>
            <div class="flex items-center gap-1.5"><div class="w-3 h-3 rounded-sm bg-blue-100 border-t border-blue-500"></div>≥ Rata-rata</div>
            <div class="flex items-center gap-1.5"><div class="w-3 h-3 rounded-sm bg-slate-100"></div>< Rata-rata</div>
        </div>
    </div>

    <!-- Perhatian Sistem List -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm flex flex-col overflow-hidden">
        <div class="p-4 pb-3 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-900">Perhatian Sistem</h3>
            @php
                $kritisCount = $lowStockCount + $totalPendingActions;
                $statusBadgeColor = $kritisCount === 0 ? 'bg-green-50 text-green-600 border-green-100' : ($kritisCount > 5 ? 'bg-red-50 text-red-600 border-red-100' : 'bg-amber-50 text-amber-600 border-amber-100');
                $statusBadgeText = $kritisCount === 0 ? 'Aman' : $kritisCount . ' Alert';
            @endphp
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $statusBadgeColor }}">
                <span class="w-1.5 h-1.5 rounded-full mr-1 {{ $kritisCount === 0 ? 'bg-green-500' : ($kritisCount > 5 ? 'bg-red-500' : 'bg-amber-500') }}"></span>
                {{ $statusBadgeText }}
            </span>
        </div>
        <div class="flex-1 flex flex-col">
            <!-- Stok Menipis (Dinamis & Real Link) -->
            @if($lowStockCount > 0)
            <a href="{{ route('items.index') }}" class="px-4 py-2.5 border-b border-slate-50 flex gap-2.5 hover:bg-red-50/60 transition-colors bg-red-50/30 group">
                <div class="w-4.5 h-4.5 mt-0.5 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="alert-circle" class="text-red-500 w-3.5 h-3.5"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-semibold text-slate-900 group-hover:text-red-600 transition-colors">
                            {{ $lowStockCount }} Model Unit Stok Menipis
                        </h4>
                        <span class="material-symbols-outlined text-[16px] text-slate-400 group-hover:text-red-500 transition-colors">chevron_right</span>
                    </div>
                    <p class="text-xs text-slate-500 line-clamp-1 mt-0.5 truncate">{{ $lowStockPreview }}</p>
                </div>
            </a>
            @else
            <div class="px-4 py-2.5 border-b border-slate-50 flex gap-2.5 hover:bg-slate-50 transition-colors bg-green-50/40">
                <div class="w-4.5 h-4.5 mt-0.5 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="check-circle-2" class="text-green-500 w-3.5 h-3.5"></i>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-slate-900">Stok Barang Aman</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Semua model peralatan memiliki ketersediaan unit yang cukup.</p>
                </div>
            </div>
            @endif

            <!-- Pending Actions / Belum Disetujui (Dinamis & Real Link) -->
            @if($totalPendingActions > 0)
            <a href="{{ route('peminjaman.index') }}" class="px-4 py-2.5 border-b border-slate-50 flex gap-2.5 hover:bg-amber-50/60 transition-colors bg-amber-50/30 group">
                <div class="w-4.5 h-4.5 mt-0.5 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="alert-triangle" class="text-amber-500 w-3.5 h-3.5"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-semibold text-slate-900 group-hover:text-amber-600 transition-colors">
                            {{ $totalPendingActions }} Tindakan Perlu Diproses
                        </h4>
                        <span class="material-symbols-outlined text-[16px] text-slate-400 group-hover:text-amber-500 transition-colors">chevron_right</span>
                    </div>
                    <p class="text-xs text-slate-500 line-clamp-1 mt-0.5 truncate">{{ $pendingPreview }}</p>
                </div>
            </a>
            @else
            <div class="px-4 py-2.5 border-b border-slate-50 flex gap-2.5 hover:bg-slate-50 transition-colors bg-green-50/40">
                <div class="w-4.5 h-4.5 mt-0.5 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="clipboard-check" class="text-green-500 w-3.5 h-3.5"></i>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-slate-900">Semua Proses Selesai</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Tidak ada tindakan atau peminjaman yang tertunda.</p>
                </div>
            </div>
            @endif

            <!-- Sinkronisasi Terakhir (Dinamis) -->
            <div class="px-4 py-2.5 flex gap-2.5 hover:bg-slate-50 transition-colors {{ $isSystemHealthy ? 'bg-blue-50/40' : 'bg-amber-50/40' }}">
                @if($isSystemHealthy)
                <div class="w-4.5 h-4.5 mt-0.5 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="info" class="text-blue-500 w-3.5 h-3.5"></i>
                </div>
                @else
                <div class="w-4.5 h-4.5 mt-0.5 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="shield-alert" class="text-amber-500 w-3.5 h-3.5"></i>
                </div>
                @endif
                <div class="min-w-0">
                    <h4 class="text-sm font-semibold text-slate-900">{{ $syncStatusLabel }}</h4>
                    <p class="text-xs text-slate-500 mt-0.5 whitespace-nowrap">{{ $lastSyncText }}</p>
                </div>
            </div>
        </div>
        <div class="px-4 py-2.5 border-t border-slate-100 bg-slate-50 mt-auto text-center">
            <a class="inline-flex items-center justify-center gap-1 text-blue-600 text-xs font-semibold hover:text-blue-800 transition-colors uppercase py-1.5 px-3 rounded-md hover:bg-blue-100/50"
               href="{{ Auth::user()->role === 'Superadmin' ? route('activity-log.index') : route('peminjaman.index') }}">
                Lihat Detail Aktivitas
                <i data-lucide="arrow-right" class="w-3.5 h-3.5 ml-1"></i>
            </a>
        </div>
    </div>
</div>

<script>
    // Close export & period dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        const exportMenu = document.getElementById('exportMenu');
        if (exportMenu && !exportMenu.classList.contains('hidden') && !e.target.closest('#exportMenu') && !e.target.closest('button[onclick*="exportMenu"]')) {
            exportMenu.classList.add('hidden');
        }
        const periodMenu = document.getElementById('periodMenu');
        if (periodMenu && !periodMenu.classList.contains('hidden') && !e.target.closest('#periodMenu') && !e.target.closest('button[onclick*="periodMenu"]')) {
            periodMenu.classList.add('hidden');
        }
    });
</script>
@endsection