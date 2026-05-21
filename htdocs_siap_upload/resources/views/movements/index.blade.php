<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Mutasi Barang - ERP NOC</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #F1F5F9;
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }
    </style>
    <style>
        html { zoom: 0.9; }
        /* Fix viewport height when zoomed */
        .min-h-screen { min-height: calc(100vh / 0.9) !important; }
        .h-screen { height: calc(100vh / 0.9) !important; }
        
        /* Consistent table header styling */
        table thead {
            background-color: #e5e7eb !important;
            border-bottom: 1px solid #d1d5db !important;
        }
        table thead th {
            color: #1f2937 !important;
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            padding: 1rem 1.5rem !important;
        }
    </style>
</head>
<body class="flex min-h-screen bg-[#F8FAFC]">

    @include('partials.sidebar')

    <!-- BEGIN: Main Content Area -->
    <main class="grow flex flex-col h-screen overflow-y-auto">
        @include('partials.topbar')

        <!-- BEGIN: Page Content -->
        <div class="p-4 md:p-10 pt-4 md:pt-6 space-y-6">
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

            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Mutasi Barang</h2>
                    <p class="text-sm text-gray-500 mt-1">Riwayat pergerakan dan mutasi barang elektronik</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('movements.create') }}" class="flex items-center gap-2 px-4 py-2 bg-[#3F51B5] text-white font-semibold rounded-lg hover:bg-[#3949AB] transition-all shadow-sm active:scale-95 text-sm">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        Catat Mutasi
                    </a>
                </div>
            </div>

            {{-- Filter Bar --}}
            <form id="filterForm" action="{{ route('movements.index') }}" method="GET" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-wrap items-center gap-3">
                <div class="relative grow min-w-50">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
                    <input type="text" name="search" class="w-full pl-10 pr-4 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] outline-none transition-all" placeholder="Cari nama peminjam..." value="{{ request('search') }}">
                </div>
                <select name="type" class="px-4 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-[#3F51B5] outline-none cursor-pointer bg-white">
                    <option value="">Semua Tipe</option>
                    <option value="masuk" {{ request('type') == 'masuk' ? 'selected' : '' }}>Barang Masuk</option>
                    <option value="keluar" {{ request('type') == 'keluar' ? 'selected' : '' }}>Barang Keluar</option>
                    <option value="pindah" {{ request('type') == 'pindah' ? 'selected' : '' }}>Pindah Lokasi</option>
                    <option value="maintenance" {{ request('type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="rusak" {{ request('type') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    <option value="musnahkan" {{ request('type') == 'musnahkan' ? 'selected' : '' }}>Dimusnahkan</option>
                </select>
                <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all text-sm border border-gray-200">
                    <span class="material-symbols-outlined text-[16px]">filter_list</span> Filter
                </button>
                @if(request()->hasAny(['type', 'item_id']))
                    <a href="{{ route('movements.index') }}" class="flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 font-semibold rounded-xl hover:bg-red-100 transition-all text-sm border border-red-200">
                        <span class="material-symbols-outlined text-[16px]">close</span> Reset
                    </a>
                @endif
            </form>

            {{-- Table Card --}}
            <div id="tableContainer" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider w-12 text-center">No</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Barang</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Tipe</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Dari</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Ke</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Qty</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">User</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($movements as $i => $mv)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-6 text-sm text-gray-500 text-center">{{ $movements->firstItem() + $i }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600 font-medium">{{ $mv->movement_date->format('d M Y') }}</td>
                                    <td class="py-4 px-6 font-semibold text-sm text-gray-800">{{ $mv->item->name ?? '-' }}</td>
                                    <td class="py-4 px-6">
                                        @php
                                            $typeClass = match($mv->type) {
                                                'masuk' => 'bg-green-100 text-green-700',
                                                'keluar' => 'bg-red-100 text-red-700',
                                                'pindah' => 'bg-blue-100 text-blue-700',
                                                'maintenance' => 'bg-yellow-100 text-yellow-700',
                                                default => 'bg-gray-100 text-gray-700',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold {{ $typeClass }}">
                                            {{ $mv->type_label }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $mv->fromLocation->name ?? '-' }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $mv->toLocation->name ?? '-' }}</td>
                                    <td class="py-4 px-6 text-sm font-semibold text-gray-800 text-center">{{ $mv->quantity }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $mv->user->name ?? '-' }}</td>
                                    <td class="py-4 px-6 text-xs text-gray-500 max-w-[200px] truncate" title="{{ $mv->notes }}">{{ $mv->notes ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-24 text-center text-gray-400">
                                        <span class="material-symbols-outlined text-[64px] mb-4 opacity-20">swap_horiz</span>
                                        <div class="font-semibold text-gray-600">Belum ada riwayat mutasi</div>
                                        <div class="text-xs mt-1">Data perpindahan dan mutasi barang akan muncul di sini.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($movements->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $movements->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>

        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('filterForm');
            const tableContainer = document.getElementById('tableContainer');

            // Handle submission
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                fetchFilteredData();
            });

            // Listen to select changes for auto-submit
            form.querySelectorAll('select').forEach(select => {
                select.addEventListener('change', fetchFilteredData);
            });

            // Handle pagination via AJAX
            document.addEventListener('click', function (e) {
                const pageLink = e.target.closest('#tableContainer a[href*="page="]');
                if (pageLink) {
                    e.preventDefault();
                    fetchFilteredData(pageLink.href);
                }
            });

            function fetchFilteredData(url = null) {
                if (!url) {
                    const formData = new FormData(form);
                    const params = new URLSearchParams(formData);
                    url = `${form.action}?${params.toString()}`;
                }

                tableContainer.style.opacity = '0.5';
                tableContainer.style.pointerEvents = 'none';

                window.history.pushState({}, '', url);

                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContainer = doc.getElementById('tableContainer');
                    if (newContainer) {
                        tableContainer.innerHTML = newContainer.innerHTML;
                    }
                })
                .catch(err => console.error(err))
                .finally(() => {
                    tableContainer.style.opacity = '1';
                    tableContainer.style.pointerEvents = 'auto';
                });
            }
        });
    </script>
    @include('components.accessibility-button')
</body>
</html>



