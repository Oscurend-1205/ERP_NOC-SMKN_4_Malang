@extends('layouts.app')

@section('title', 'Dashboard')

@push('scripts')
<!-- ApexCharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endpush

@section('content')
    <!-- BEGIN: Ringkasan Section -->
    <section data-purpose="summary-stats">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Ringkasan Dashboard</h2>
            <p class="text-sm text-gray-500 mt-1">Pantau data inventaris NOC secara real-time</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Card 1: Total Aset NOC -->
            <div class="bg-white rounded-2xl border border-gray-100 p-6 flex items-start justify-between shadow-sm hover:shadow-md transition-all relative overflow-hidden before:absolute before:top-0 before:left-0 before:right-0 before:h-[3px] before:bg-[#3F51B5]">
                <div>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Aset NOC</h3>
                    <div class="text-3xl font-extrabold text-gray-800 mt-2">{{ $totalItems }}</div>
                    <div class="text-xs text-gray-400 mt-1">Seluruh barang terdaftar</div>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-[#EDE7F6] text-[#3F51B5] flex-shrink-0">
                    <img alt="Total Aset Icon" class="w-8 h-8 object-contain" src="{{ asset('asset/icon/total-aset-noc.svg') }}"/>
                </div>
            </div>
            <!-- Card 2: Peminjaman Aktif -->
            <div class="bg-white rounded-2xl border border-gray-100 p-6 flex items-start justify-between shadow-sm hover:shadow-md transition-all relative overflow-hidden before:absolute before:top-0 before:left-0 before:right-0 before:h-[3px] before:bg-[#FF9800]">
                <div>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Peminjaman Aktif</h3>
                    <div class="text-3xl font-extrabold text-gray-800 mt-2">{{ $itemsMaintenance ?? 0 }}</div>
                    <div class="text-xs text-gray-400 mt-1">Sedang dipinjam</div>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-[#FFF3E0] text-[#FF9800] flex-shrink-0">
                    <img alt="Peminjaman Aktif Icon" class="w-8 h-8 object-contain" src="{{ asset('asset/icon/peminjaman-aktif.svg') }}"/>
                </div>
            </div>
            <!-- Card 3: Sisa Barang -->
            <div class="bg-white rounded-2xl border border-gray-100 p-6 flex items-start justify-between shadow-sm hover:shadow-md transition-all relative overflow-hidden before:absolute before:top-0 before:left-0 before:right-0 before:h-[3px] before:bg-[#4CAF50]">
                <div>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sisa Barang</h3>
                    <div class="text-3xl font-extrabold text-gray-800 mt-2">{{ $itemsBaik }}</div>
                    <div class="text-xs text-gray-400 mt-1">Kondisi baik & tersedia</div>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-[#E8F5E9] text-[#4CAF50] flex-shrink-0">
                    <img alt="Sisa Barang Icon" class="w-8 h-8 object-contain" src="{{ asset('asset/icon/sisa-barang.svg') }}"/>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Ringkasan Section -->

    <!-- BEGIN: Charts Section -->
    <section data-purpose="dashboard-charts">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Card 1: Bar Chart -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-all">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Aktivitas Barang Masuk</h2>
                    <select class="px-3 py-1.5 text-xs font-medium border border-gray-200 rounded-lg text-gray-500 outline-none bg-white cursor-pointer hover:border-gray-300 transition-colors">
                        <option>This Year</option>
                        <option>Last Year</option>
                    </select>
                </div>
                <div class="p-6">
                    <div id="barChart" class="w-full h-[200px]"></div>
                </div>
            </div>

            <!-- Card 2: Donut Chart -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-all">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Distribusi Kondisi Barang</h2>
                </div>
                <div class="p-6 flex flex-col items-center justify-center">
                    <div id="donutChart" class="w-full flex justify-center"></div>
                    <div class="flex justify-center gap-6 mt-3">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#1A73E8] inline-block"></span>
                            <span class="text-xs font-medium text-gray-500">Baru (75%)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#B85D19] inline-block"></span>
                            <span class="text-xs font-medium text-gray-500">Bekas (25%)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Charts Section -->

    <!-- BEGIN: Activity Table Section -->
    <section data-purpose="activity-table-container">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-all">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Aktivitas Terkini</h2>
                <button class="text-xs font-bold text-[#3F51B5] hover:text-[#3949AB] bg-transparent border-none cursor-pointer transition-colors">Lihat Semua</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Tanggal</th>
                            <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Nama Barang</th>
                            <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Tipe</th>
                            <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Kuantitas</th>
                            <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Status</th>
                            <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentMovements as $movement)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6 text-sm text-gray-600 text-center">{{ $movement->created_at->format('H:i d/m/Y') }}</td>
                            <td class="py-4 px-6 text-sm font-semibold text-gray-800 text-center">{{ $movement->item->name ?? 'N/A' }}</td>
                            <td class="py-4 px-6 text-sm text-gray-600 text-center capitalize">{{ $movement->type }}</td>
                            <td class="py-4 px-6 text-sm text-gray-800 text-center font-medium">{{ $movement->quantity }} Unit</td>
                            <td class="py-4 px-6 text-center">
                                @php
                                    $badgeClass = match($movement->type) {
                                        'masuk' => 'bg-green-100 text-green-700',
                                        'keluar' => 'bg-red-100 text-red-700',
                                        'pindah' => 'bg-blue-100 text-blue-700',
                                        default => 'bg-gray-100 text-gray-700'
                                    };
                                    $badgeText = match($movement->type) {
                                        'masuk' => 'Masuk',
                                        'keluar' => 'Keluar',
                                        'pindah' => 'Dipinjam',
                                        default => ucfirst($movement->type)
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold {{ $badgeClass }}">{{ $badgeText }}</span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <a href="{{ route('items.show', $movement->item_id) }}" class="p-1.5 text-gray-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg inline-flex items-center justify-center transition-colors" title="Lihat Detail">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        @if($recentMovements->isEmpty())
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                <span class="material-symbols-outlined text-[48px] mb-2 opacity-20">inbox</span>
                                <p class="text-sm font-medium">Belum ada aktivitas terbaru</p>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- END: Activity Table Section -->

<script>
    // Realtime Clock
    function updateClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        const el = document.getElementById('realtime-clock-display');
        if (el) el.textContent = h + ':' + m + ':' + s;

        const hour = now.getHours();
        const statusEl = document.getElementById('operational-status');
        if (statusEl) {
            if (hour >= 6 && hour < 15) {
                statusEl.textContent = 'OPEN';
                statusEl.className = 'font-bold text-[12px] text-green-400';
            } else {
                statusEl.textContent = 'CLOSED';
                statusEl.className = 'font-bold text-[12px] text-red-400';
            }
        }
    }
    updateClock();
    setInterval(updateClock, 1000);

    // Phone number input formatting
    const phoneInput = document.getElementById('borrower_phone_input');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    // Initialize ApexCharts
    document.addEventListener('DOMContentLoaded', function() {
        // Bar Chart
        var barOptions = {
            series: [{
                name: 'Barang Masuk',
                data: [30, 55, 80, 45, 110, 75, 35]
            }],
            chart: {
                type: 'bar',
                height: 200,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            colors: ['#1A73E8'],
            plotOptions: {
                bar: {
                    columnWidth: '100%',
                    borderRadius: 0,
                }
            },
            dataLabels: { enabled: false },
            stroke: { show: false },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#9CA3AF', fontSize: '12px' } }
            },
            yaxis: { show: false },
            grid: {
                borderColor: '#F3F4F6',
                strokeDashArray: 4,
                xaxis: { lines: { show: false } },
                yaxis: { lines: { show: true } },
                padding: { top: 0, right: 0, bottom: 0, left: 10 }
            },
            tooltip: {
                enabled: true,
                y: { formatter: function(val) { return val + " Unit" } }
            }
        };
        if(document.querySelector("#barChart")) {
            var barChart = new ApexCharts(document.querySelector("#barChart"), barOptions);
            barChart.render();
        }

        // Donut Chart
        var donutOptions = {
            series: [75, 25],
            labels: ['Baru', 'Bekas'],
            chart: {
                type: 'donut',
                height: 180,
                fontFamily: 'Inter, sans-serif'
            },
            colors: ['#1A73E8', '#B85D19'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '24px',
                                fontWeight: 700,
                                color: '#1F2937',
                                offsetY: 5
                            },
                            value: {
                                show: true,
                                fontSize: '12px',
                                color: '#6B7280',
                                offsetY: 5,
                                formatter: function (val) {
                                    return "Total"
                                }
                            },
                            total: {
                                show: true,
                                showAlways: true,
                                label: '245',
                                fontSize: '24px',
                                fontWeight: 700,
                                color: '#1F2937',
                                formatter: function (w) {
                                    return "Total"
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
            legend: { show: false },
            stroke: { width: 0 },
            tooltip: { enabled: true }
        };
        if(document.querySelector("#donutChart")) {
            var donutChart = new ApexCharts(document.querySelector("#donutChart"), donutOptions);
            donutChart.render();
        }
    });
</script>
@endsection
