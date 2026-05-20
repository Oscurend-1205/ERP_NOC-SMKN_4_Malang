<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Barang Elektronik - ERP NOC</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }
    </style>
</head>
<body class="flex min-h-screen bg-[#F8FAFC]">

    @include('partials.sidebar')

    <!-- BEGIN: Main Content Area -->
    <main class="flex-grow flex flex-col h-screen overflow-y-auto">
        @include('partials.topbar')

        <!-- BEGIN: Page Content -->
        <div class="p-10 space-y-8">
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
                    <h2 class="text-2xl font-bold text-gray-800">Barang Elektronik</h2>
                    <p class="text-sm text-gray-500 mt-1">Kelola inventaris barang elektronik laboratorium</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('items.scan-input') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 font-semibold rounded-lg hover:bg-blue-100 transition-all shadow-sm active:scale-95 text-sm border border-blue-200">
                        <span class="material-symbols-outlined text-[18px]">qr_code_scanner</span> 
                        Scan & Tambah
                    </a>
                    <a href="{{ route('items.create') }}" class="flex items-center gap-2 px-4 py-2 bg-[#3F51B5] text-white font-semibold rounded-lg hover:bg-[#3949AB] transition-all shadow-sm active:scale-95 text-sm">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        Tambah Barang
                    </a>
                </div>
            </div>

            {{-- Filter Bar --}}
            <form id="filterForm" action="{{ route('items.index') }}" method="GET" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-wrap items-center gap-3">
                <div class="relative flex-grow min-w-[200px]">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
                    <input type="text" name="search" class="w-full pl-10 pr-4 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] outline-none transition-all" placeholder="Cari nama, kode, merek..." value="{{ request('search') }}">
                </div>
                <select name="category_id" class="px-4 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-[#3F51B5] outline-none cursor-pointer bg-white">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="location_id" class="px-4 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-[#3F51B5] outline-none cursor-pointer bg-white">
                    <option value="">Semua Lokasi</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                    @endforeach
                </select>
                <select name="condition" class="px-4 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-[#3F51B5] outline-none cursor-pointer bg-white">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" {{ request('condition') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak_ringan" {{ request('condition') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak_berat" {{ request('condition') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                    <option value="hilang" {{ request('condition') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                </select>
                <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all text-sm border border-gray-200">
                    <span class="material-symbols-outlined text-[16px]">filter_list</span> Filter
                </button>
                @if(request()->hasAny(['search', 'category_id', 'location_id', 'condition', 'status']))
                    <a href="{{ route('items.index') }}" class="flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 font-semibold rounded-xl hover:bg-red-100 transition-all text-sm border border-red-200">
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
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Kode</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Merek</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Lokasi</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Qty</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Kondisi</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($items as $i => $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-6 text-sm text-gray-500 text-center">{{ $items->firstItem() + $i }}</td>
                                    <td class="py-4 px-6">
                                        <code class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded font-mono">{{ $item->code }}</code>
                                    </td>
                                    <td class="py-4 px-6 font-semibold text-sm text-gray-800">{{ $item->name }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $item->brand ?? '-' }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $item->category->name }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $item->location->name }}</td>
                                    <td class="py-4 px-6 text-sm font-semibold text-gray-800 text-center">{{ $item->quantity }}</td>
                                    <td class="py-4 px-6">
                                        @php
                                            $condClass = match($item->condition) {
                                                'baik' => 'bg-green-100 text-green-700',
                                                'rusak_ringan' => 'bg-yellow-100 text-yellow-700',
                                                'rusak_berat' => 'bg-red-100 text-red-700',
                                                'hilang' => 'bg-gray-100 text-gray-700',
                                                default => 'bg-gray-100 text-gray-700',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold {{ $condClass }}">
                                            {{ $item->condition_label }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        @php
                                            $statusClass = match($item->status) {
                                                'tersedia' => 'bg-green-100 text-green-700',
                                                'dipinjam' => 'bg-blue-100 text-blue-700',
                                                'maintenance' => 'bg-orange-100 text-orange-700',
                                                'dimusnahkan' => 'bg-red-100 text-red-700',
                                                default => 'bg-gray-100 text-gray-700',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold {{ $statusClass }}">
                                            {{ $item->status_label }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('items.edit', $item) }}" class="p-1.5 text-gray-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                                <span class="material-symbols-outlined text-[20px]">edit</span>
                                            </a>
                                            <form action="{{ route('items.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin hapus barang ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="py-24 text-center text-gray-400">
                                        <span class="material-symbols-outlined text-[64px] mb-4 opacity-20">inventory_2</span>
                                        <div class="font-semibold text-gray-600">Belum ada barang</div>
                                        <div class="text-xs mt-1">Tambahkan barang pertama untuk memulai inventaris!</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($items->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $items->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>

        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('filterForm');
            const tableContainer = document.getElementById('tableContainer');
            let debounceTimer;

            // Handle submission and changes
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                fetchFilteredData();
            });

            // Listen to select changes
            form.querySelectorAll('select').forEach(select => {
                select.addEventListener('change', fetchFilteredData);
            });

            // Listen to search input with debounce
            const searchInput = form.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(fetchFilteredData, 500);
                });
            }

            // Also intercept pagination links to load via AJAX
            document.addEventListener('click', function (e) {
                const target = e.target.closest('.pagination a');
                // The pagination links in Laravel might not have .pagination class by default in Tailwind, 
                // but we can check if it's an anchor inside the tableContainer's pagination area.
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

                // Show basic loading state
                tableContainer.style.opacity = '0.5';
                tableContainer.style.pointerEvents = 'none';

                // Update browser URL without reloading
                window.history.pushState({}, '', url);

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTableContainer = doc.getElementById('tableContainer');
                    
                    if (newTableContainer) {
                        tableContainer.innerHTML = newTableContainer.innerHTML;
                    }
                })
                .catch(error => console.error('Error fetching data:', error))
                .finally(() => {
                    tableContainer.style.opacity = '1';
                    tableContainer.style.pointerEvents = 'auto';
                });
            }
        });
    </script>
</body>
</html>
