<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Barang Masuk - ERP NOC</title>
    <!-- Tailwind CSS CDN with plugins -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style data-purpose="typography">
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
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
            padding: 0.75rem 1rem !important;
        }
        /* Elegant minimalist table cells */
        table tbody td {
            padding: 0.5rem 1rem !important;
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden bg-[#F8FAFC]">

    @include('partials.sidebar')

    <!-- BEGIN: Main Content Area -->
    <main class="flex-grow flex flex-col h-screen overflow-y-auto">
        @include('partials.topbar')

        <!-- BEGIN: Main Page Content -->
        <div id="pjax-content" class="p-4 md:p-10 pt-4 md:pt-6 space-y-6" data-purpose="main-layout">
            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Data Barang Masuk</h2>
                    <p class="text-sm text-gray-500 mt-1">Riwayat penerimaan barang masuk aset NOC</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('items.index') }}" class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-600 font-semibold rounded-lg hover:bg-gray-200 transition-all text-sm border border-gray-200">
                        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                        Kembali
                    </a>
                    <button onclick="document.getElementById('addBarangMasukModal').classList.remove('hidden')" class="flex items-center gap-2 px-4 py-2 bg-[#3F51B5] text-white font-semibold rounded-lg hover:bg-[#3949AB] transition-all shadow-sm active:scale-95 text-sm">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        Tambah Barang Masuk
                    </button>
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
            <form method="GET" action="{{ route('items.barang-masuk') }}" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-wrap items-center gap-3">
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
                    <select name="condition" onchange="this.form.submit()" class="appearance-none pl-4 pr-9 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-[#3F51B5] outline-none cursor-pointer bg-white text-gray-700">
                        <option value="">Semua Kondisi</option>
                        <option value="baik" {{ request('condition') === 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak_ringan" {{ request('condition') === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="rusak_berat" {{ request('condition') === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
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
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari barang..." class="pl-9 pr-4 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-[#3F51B5] outline-none w-56 text-gray-700 placeholder-gray-400" />
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
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Masuk</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">ID Barang</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Kondisi</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($movements as $index => $movement)
                                @php
                                    $item = $movement->item;
                                    $conditionLabel = $item ? $item->condition_label : '-';
                                    $conditionClass = match($item?->condition) {
                                        'baik' => 'bg-green-100 text-green-700',
                                        'rusak_ringan' => 'bg-yellow-100 text-yellow-700',
                                        'rusak_berat' => 'bg-red-100 text-red-700',
                                        default => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-6 text-sm text-gray-500 text-center">{{ $movements->firstItem() + $index }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ \Carbon\Carbon::parse($movement->movement_date)->format('d M Y') }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600 font-mono">{{ $item?->code ?? '-' }}</td>
                                    <td class="py-4 px-6 font-semibold text-sm text-gray-800">{{ $item?->name ?? '-' }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $item?->category?->name ?? '-' }}</td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold {{ $conditionClass }}">{{ $conditionLabel }}</span>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick="showDetailModal({{ $movement->id }}, '{{ $item?->name ?? '-' }}', '{{ $item?->code ?? '-' }}', '{{ \Carbon\Carbon::parse($movement->movement_date)->format('d M Y') }}', '{{ $item?->category?->name ?? '-' }}', '{{ $conditionLabel }}', {{ $movement->quantity }}, '{{ $movement->notes ?? '-' }}', '{{ $movement->user?->name ?? '-' }}')" class="text-[#3F51B5] hover:underline font-medium text-sm">Detail</button>
                                            @if(auth()->user()->role === 'Superadmin')
                                                <form method="POST" action="{{ route('items.barang-masuk.destroy', $movement->id) }}" onsubmit="return confirm('Yakin ingin menghapus data barang masuk ini? Stok barang akan dikurangi kembali.')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:underline font-medium text-sm">Hapus</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <span class="material-symbols-outlined text-5xl text-gray-300 mb-3">inventory_2</span>
                                            <p class="text-gray-500 text-sm">Belum ada data barang masuk.</p>
                                            <p class="text-gray-400 text-xs mt-1">Klik tombol "Tambah Barang Masuk" untuk menambahkan data.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($movements->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $movements->links() }}
                    </div>
                @endif
            </div>
        </div>
        <!-- END: Main Page Content -->
    </main>
    <!-- END: Main Content Area -->

    {{-- Modal: Tambah Barang Masuk --}}
    <div id="addBarangMasukModal" class="hidden fixed inset-0 z-[90] flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('addBarangMasukModal').classList.add('hidden')"></div>
        
        <!-- Modal Content -->
        <div class="relative w-full max-w-[500px] bg-white rounded-2xl shadow-2xl flex flex-col font-sans max-h-[90vh] overflow-hidden">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-white rounded-t-2xl">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Tambah Barang Masuk</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Catat penerimaan barang baru ke dalam aset NOC</p>
                </div>
                <button onclick="document.getElementById('addBarangMasukModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 bg-gray-50">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <!-- Body -->
            <form method="POST" action="{{ route('items.barang-masuk.store') }}" class="px-6 py-5 overflow-y-auto bg-gray-50 flex-1 rounded-b-2xl space-y-4">
                @csrf

                {{-- Pilih Barang --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Pilih Barang <span class="text-red-500">*</span></label>
                    <select name="item_id" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#3F51B5] outline-none bg-white text-gray-700">
                        <option value="" disabled selected hidden>-- Pilih Barang --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}">{{ $item->code }} — {{ $item->name }} {{ $item->brand ? '('.$item->brand.')' : '' }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Jumlah --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jumlah <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" min="1" value="1" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#3F51B5] outline-none bg-white text-gray-700" />
                </div>

                {{-- Tanggal Masuk --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tanggal Masuk <span class="text-red-500">*</span></label>
                    <input type="date" name="movement_date" value="{{ date('Y-m-d') }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#3F51B5] outline-none bg-white text-gray-700" />
                </div>

                {{-- Lokasi Tujuan --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Lokasi Tujuan <span class="text-gray-400 text-[10px]">(opsional)</span></label>
                    <select name="to_location_id" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#3F51B5] outline-none bg-white text-gray-700">
                        <option value="">-- Tidak Dipilih --</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Catatan <span class="text-gray-400 text-[10px]">(opsional)</span></label>
                    <textarea name="notes" rows="3" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#3F51B5] outline-none bg-white text-gray-700 resize-none" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('addBarangMasukModal').classList.add('hidden')" class="px-5 py-2.5 bg-gray-100 text-gray-600 font-semibold rounded-xl hover:bg-gray-200 transition-all text-sm border border-gray-200">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-[#3F51B5] text-white font-semibold rounded-xl hover:bg-[#3949AB] transition-all shadow-sm active:scale-95 text-sm">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Detail Barang Masuk --}}
    <div id="detailModal" class="hidden fixed inset-0 z-[90] flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('detailModal').classList.add('hidden')"></div>
        
        <!-- Modal Content -->
        <div class="relative w-full max-w-[450px] bg-white rounded-2xl shadow-2xl flex flex-col font-sans max-h-[90vh] overflow-hidden">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-white rounded-t-2xl">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Detail Barang Masuk</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Informasi lengkap penerimaan barang</p>
                </div>
                <button onclick="document.getElementById('detailModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 bg-gray-50">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <!-- Body -->
            <div class="px-6 py-5 overflow-y-auto bg-gray-50 flex-1 rounded-b-2xl space-y-3">
                <div class="flex justify-between items-start py-2 border-b border-gray-100">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Barang</span>
                    <span id="detail_nama" class="text-sm font-semibold text-gray-800 text-right max-w-[60%]">-</span>
                </div>
                <div class="flex justify-between items-start py-2 border-b border-gray-100">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode Barang</span>
                    <span id="detail_kode" class="text-sm text-gray-700 font-mono">-</span>
                </div>
                <div class="flex justify-between items-start py-2 border-b border-gray-100">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Masuk</span>
                    <span id="detail_tanggal" class="text-sm text-gray-700">-</span>
                </div>
                <div class="flex justify-between items-start py-2 border-b border-gray-100">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</span>
                    <span id="detail_kategori" class="text-sm text-gray-700">-</span>
                </div>
                <div class="flex justify-between items-start py-2 border-b border-gray-100">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kondisi</span>
                    <span id="detail_kondisi" class="text-sm text-gray-700">-</span>
                </div>
                <div class="flex justify-between items-start py-2 border-b border-gray-100">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah</span>
                    <span id="detail_jumlah" class="text-sm font-semibold text-gray-800">-</span>
                </div>
                <div class="flex justify-between items-start py-2 border-b border-gray-100">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Dicatat Oleh</span>
                    <span id="detail_user" class="text-sm text-gray-700">-</span>
                </div>
                <div class="flex justify-between items-start py-2">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Catatan</span>
                    <span id="detail_catatan" class="text-sm text-gray-700 text-right max-w-[60%]">-</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDetailModal(id, nama, kode, tanggal, kategori, kondisi, jumlah, catatan, user) {
            document.getElementById('detail_nama').textContent = nama;
            document.getElementById('detail_kode').textContent = kode;
            document.getElementById('detail_tanggal').textContent = tanggal;
            document.getElementById('detail_kategori').textContent = kategori;
            document.getElementById('detail_kondisi').textContent = kondisi;
            document.getElementById('detail_jumlah').textContent = jumlah + ' unit';
            document.getElementById('detail_catatan').textContent = catatan || '-';
            document.getElementById('detail_user').textContent = user || '-';
            document.getElementById('detailModal').classList.remove('hidden');
        }
    </script>

    @vite(['resources/js/turbo-navigation.js'])
    @include('components.accessibility-button')
</body>
</html>
