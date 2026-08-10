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
                    
                    <button onclick="toggleAddBarangMasukModal(true)" class="flex items-center gap-2 px-4 py-2 bg-[#3F51B5] text-white font-semibold rounded-lg hover:bg-[#3949AB] transition-all shadow-sm active:scale-95 text-sm">
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

            
            {{-- Alert Masa Tenggang --}}
            @if(isset($masaTenggang) && $masaTenggang->count() > 0)
                <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-xl shadow-sm mb-6 flex-1">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-0.5">
                            <span class="material-symbols-outlined text-amber-500 text-[20px]">warning</span>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-amber-800">Peringatan: Masa Tenggang Peminjaman</h3>
                            <div class="mt-2 text-sm text-amber-700">
                                <p>Terdapat {{ $masaTenggang->count() }} barang pinjaman yang mendekati atau melewati batas waktu pengembalian:</p>
                                <ul class="list-disc pl-5 mt-1.5 space-y-1">
                                    @foreach($masaTenggang->take(5) as $tenggang)
                                        @php
                                            $sisaHari = \Carbon\Carbon::now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($tenggang->rentang_waktu_peminjaman)->startOfDay(), false);
                                            $badgeText = $sisaHari < 0 ? 'Terlewat ' . abs($sisaHari) . ' hari' : ($sisaHari == 0 ? 'Hari ini' : 'Sisa ' . $sisaHari . ' hari');
                                            $badgeClass = $sisaHari < 0 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700';
                                        @endphp
                                        <li>
                                            <span class="font-semibold">{{ $tenggang->item->name ?? 'Barang tidak diketahui' }}</span> 
                                            <span class="text-xs text-amber-600">({{ $tenggang->item->code ?? '-' }})</span> 
                                            - Batas: <span class="font-medium">{{ \Carbon\Carbon::parse($tenggang->rentang_waktu_peminjaman)->format('d M Y') }}</span>
                                            <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold {{ $badgeClass }}">{{ $badgeText }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                @if($masaTenggang->count() > 5)
                                    <p class="text-xs mt-2 italic text-amber-600">Dan {{ $masaTenggang->count() - 5 }} barang lainnya...</p>
                                @endif
                            </div>
                        </div>
                    </div>
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
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis Masuk</th>
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
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $movement->jenis_barang_masuk ?? '-' }}</td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold {{ $conditionClass }}">{{ $conditionLabel }}</span>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick="showDetailModal({{ $movement->id }}, '{{ $item?->name ?? '-' }}', '{{ $item?->code ?? '-' }}', '{{ \Carbon\Carbon::parse($movement->movement_date)->format('d M Y') }}', '{{ $item?->category?->name ?? '-' }}', '{{ $conditionLabel }}', {{ $movement->quantity }}, '{{ $movement->notes ?? '-' }}', '{{ $movement->user?->name ?? '-' }}', '{{ $movement->jenis_barang_masuk ?? '-' }}', '{{ $movement->rentang_waktu_peminjaman ? \Carbon\Carbon::parse($movement->rentang_waktu_peminjaman)->format('d M Y') : '-' }}', '{{ $movement->biaya_peminjaman ?? '' }}')" class="text-[#3F51B5] hover:underline font-medium text-sm">Detail</button>
                                            @if(auth()->user()->role === 'Superadmin')
                                                <form method="POST" action="{{ route('items.barang-masuk.destroy', $movement->id) }}" data-confirm="Yakin ingin menghapus data barang masuk ini? Stok barang akan dikurangi kembali." class="inline">
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

        <!-- Modal Tambah Barang Masuk -->
    <div id="addBarangMasukModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="toggleAddBarangMasukModal(false)"></div>
        
        <div class="relative w-full max-w-[1000px] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[92vh] font-sans">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#3F51B5] to-[#5C6BC0]">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-[22px]">add_box</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white">Tambah Barang Masuk</h2>
                        <p class="text-xs text-white/70 mt-0.5">Catat penerimaan barang baru ke dalam aset NOC</p>
                    </div>
                </div>
                <button onclick="toggleAddBarangMasukModal(false)" class="text-white/70 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <form id="addBarangForm" action="{{ route('items.barang-masuk.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                
                <!-- STEP 1 -->
                <div id="formStep1BM" class="flex flex-col flex-1 overflow-hidden">
                <div class="px-6 py-5 space-y-5 overflow-y-auto flex-1">

                    <input type="hidden" name="item_type" value="new">
                    {{-- SECTION: Detail Masuk --}}
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="material-symbols-outlined text-[#3F51B5] text-[18px]">input</span>
                            <h3 class="text-sm font-bold text-gray-800">Detail Masuk</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Jenis Barang Masuk --}}
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Jenis Barang Masuk <span class="text-red-500">*</span></label>
                                <select name="jenis_barang_masuk" required 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 text-gray-700 bg-white shadow-sm cursor-pointer border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Pilih Jenis</option>
                                    <option value="Hibah">Hibah</option>
                                    <option value="Pinjaman">Pinjaman</option>
                                    <option value="Pembelian">Pembelian</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            {{-- Tanggal Masuk --}}
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Tanggal Masuk <span class="text-red-500">*</span></label>
                                <input type="date" name="movement_date" value="{{ date('Y-m-d') }}" required 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 text-gray-700 bg-white shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            {{-- Rentang Waktu Peminjaman --}}
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="block text-[13px] font-semibold text-gray-700">Batas Waktu Peminjaman <span class="text-gray-400 text-[10px]">(opsional, jika pinjaman)</span></label>
                                <input type="date" name="rentang_waktu_peminjaman" 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 text-gray-700 bg-white shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <p class="text-[11px] text-gray-500">Sistem akan memberi peringatan 1 minggu sebelum batas waktu ini.</p>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION: Informasi Barang --}}
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="material-symbols-outlined text-[#3F51B5] text-[18px]">inventory_2</span>
                            <h3 class="text-sm font-bold text-gray-800">Informasi Barang</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Nama Barang --}}
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="block text-[13px] font-semibold text-gray-700">Nama Barang <span class="text-red-500">*</span></label>
                                <input type="text" name="name" required placeholder="Contoh: Access Point UniFi, Router MikroTik RB750Gr3" value="{{ old('name') }}" 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 placeholder-gray-400 shadow-sm {{ $errors->has('name') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                                @error('name') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Kategori --}}
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <label class="block text-[13px] font-semibold text-gray-700">Kategori <span class="text-red-500">*</span></label>
                                    <button type="button" onclick="openQuickCategoryModal()" class="text-[11px] text-[#3F51B5] font-semibold hover:underline flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">add_circle</span> Kategori Baru
                                    </button>
                                </div>
                                <select name="category_id" id="addBarangCategoryId" required 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 text-gray-700 bg-white shadow-sm cursor-pointer {{ $errors->has('category_id') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" data-prefix="{{ $cat->prefix }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }} ({{ $cat->prefix }})</option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Sub Prefix (Hidden) --}}
                            <input type="hidden" name="sub_prefix" id="addBarangSubPrefix" maxlength="10" value="{{ old('sub_prefix') }}">

                            {{-- Kode Preview --}}
                            <div class="space-y-1.5" id="codePreviewWrapper">
                                <label class="block text-[13px] font-semibold text-gray-700">Kode Inventaris</label>
                                <div class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-[13px] bg-gray-50 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px] text-gray-400">tag</span>
                                    <code id="codePreviewText" class="font-mono font-bold text-indigo-600 tracking-wider">Pilih kategori terlebih dahulu</code>
                                </div>
                                <p class="text-[11px] text-gray-400">Otomatis di-generate. Format: PREFIX-[SUBPREFIX-]NOMOR</p>
                            </div>

                            {{-- Merek --}}
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Merek</label>
                                <input type="text" name="brand" placeholder="Contoh: Cisco, MikroTik, TP-Link" value="{{ old('brand') }}" 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 placeholder-gray-400 shadow-sm {{ $errors->has('brand') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                                @error('brand') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Model --}}
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Model</label>
                                <input type="text" name="model" placeholder="Contoh: RB750Gr3, EAP225, TL-SG1024D" value="{{ old('model') }}" 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 placeholder-gray-400 shadow-sm {{ $errors->has('model') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                                @error('model') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>


                        </div>
                    </div>

                    {{-- SECTION: Lokasi & Stok --}}
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="material-symbols-outlined text-[#3F51B5] text-[18px]">location_on</span>
                            <h3 class="text-sm font-bold text-gray-800">Lokasi & Stok</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Lokasi --}}
                            <div class="space-y-1.5">
                                @php
                                    $nocLocation = $locations->first(function($loc) { return stripos($loc->name, 'noc') !== false; });
                                    $nocId = $nocLocation ? $nocLocation->id : ($locations->first()->id ?? '');
                                    $nocName = $nocLocation ? $nocLocation->name : ($locations->first()->name ?? '');
                                @endphp
                                <label class="block text-[13px] font-semibold text-gray-700">Lokasi <span class="text-red-500">*</span></label>
                                <select disabled class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 text-gray-700 bg-gray-100 shadow-sm cursor-not-allowed border-gray-300">
                                    <option value="{{ $nocId }}" selected>{{ $nocName }}</option>
                                </select>
                                <input type="hidden" name="location_id" value="{{ $nocId }}">
                                <p class="text-[11px] text-gray-500">Otomatis dialokasikan ke ruang NOC.</p>
                            </div>

                            {{-- Jumlah --}}
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Jumlah Unit <span class="text-red-500">*</span></label>
                                <input type="number" name="quantity" required min="1" value="{{ old('quantity', 1) }}" 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 shadow-sm {{ $errors->has('quantity') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                                <p class="text-[11px] text-gray-400">Setiap unit akan mendapat kode inventaris unik</p>
                                @error('quantity') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>



                            {{-- Status --}}
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Status <span class="text-red-500">*</span></label>
                                <select name="status" required 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 text-gray-700 bg-white shadow-sm cursor-pointer {{ $errors->has('status') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                                    <option value="tersedia" {{ old('status', 'tersedia') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="dipinjam" {{ old('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="dimusnahkan" {{ old('status') == 'dimusnahkan' ? 'selected' : '' }}>Dimusnahkan</option>
                                </select>
                                @error('status') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- SECTION: Data Perolehan --}}
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="material-symbols-outlined text-[#3F51B5] text-[18px]">shopping_cart</span>
                            <h3 class="text-sm font-bold text-gray-800">Data Perolehan</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Supplier --}}
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Supplier</label>
                                <select name="supplier_id" 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 text-gray-700 bg-white shadow-sm cursor-pointer border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Pilih Supplier</option>
                                    @foreach($suppliers as $sup)
                                        <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Asal Barang --}}
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Asal Barang</label>
                                <select name="asal_barang_id" 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 text-gray-700 bg-white shadow-sm cursor-pointer border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Pilih Asal Barang</option>
                                    @foreach($asalBarangs as $asal)
                                        <option value="{{ $asal->id }}" {{ old('asal_barang_id') == $asal->id ? 'selected' : '' }}>{{ $asal->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Kondisi Barang (Master) --}}
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Kondisi Barang (Master)</label>
                                <select name="kondisi_barang_id" 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 text-gray-700 bg-white shadow-sm cursor-pointer border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Pilih Kondisi</option>
                                    @foreach($kondisis as $k)
                                        <option value="{{ $k->id }}" {{ old('kondisi_barang_id') == $k->id ? 'selected' : '' }}>{{ $k->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Biaya Peminjaman/Sewa --}}
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="block text-[13px] font-semibold text-gray-700">Biaya Peminjaman / Sewa (opsional)</label>
                                <div class="relative flex items-center rounded-lg border shadow-sm focus-within:ring-1 bg-white overflow-hidden border-gray-300 focus-within:ring-blue-500 focus-within:border-blue-500">
                                    <span class="bg-gray-50 px-3 py-2.5 text-[13px] text-gray-500 border-r border-gray-200 select-none font-semibold">Rp</span>
                                    <input type="text" name="biaya_peminjaman" id="purchase_price_input" placeholder="0" value="{{ old('biaya_peminjaman') }}" 
                                        class="w-full border-0 pl-3 pr-1 py-2.5 text-[13px] focus:ring-0 focus:outline-none placeholder-gray-400">
                                    <span class="text-[13px] text-gray-500 pr-3 select-none">,00</span>
                                </div>
                                <p class="text-[11px] text-gray-500">Jika barang pinjaman memiliki harga sewa per periode, catat disini.</p>
                                @error('biaya_peminjaman') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- SECTION: Lainnya --}}
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="material-symbols-outlined text-[#3F51B5] text-[18px]">notes</span>
                            <h3 class="text-sm font-bold text-gray-800">Informasi Tambahan</h3>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            {{-- Catatan --}}
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Catatan</label>
                                <textarea name="notes" placeholder="Catatan tambahan (opsional)" 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 placeholder-gray-400 shadow-sm h-16 border-gray-300 focus:ring-blue-500 focus:border-blue-500">{{ old('notes') }}</textarea>
                                @error('notes') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Foto Barang --}}
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Foto Barang</label>
                                <input type="file" name="image" accept="image/jpeg,image/png,image/jpg" 
                                    class="w-full border rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 bg-white text-gray-700 shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <p class="text-[11px] text-gray-400">Format: JPG, PNG. Maks: 2MB</p>
                                @error('image') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Footer Step 1 -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3 mt-auto">
                    <button type="button" onclick="toggleAddBarangMasukModal(false)" class="px-5 py-2.5 text-[13px] font-bold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="button" onclick="nextStepBM()" class="px-5 py-2.5 text-[13px] font-bold text-white bg-[#3F51B5] rounded-lg hover:bg-[#3949AB] transition-colors shadow-sm flex items-center gap-2">
                        Lanjut
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </button>
                </div>
                </div> <!-- END STEP 1 -->
                
                <!-- STEP 2 -->
                <div id="formStep2BM" class="hidden flex flex-col flex-1 overflow-hidden">
                    <div class="px-6 py-5 space-y-5 overflow-y-auto flex-1">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="material-symbols-outlined text-[#3F51B5] text-[18px]">list_alt</span>
                            <h3 class="text-sm font-bold text-gray-800">Detail Serial Number & Kondisi Tiap Unit</h3>
                        </div>
                        <p class="text-xs text-gray-500 mb-4">Silakan isi serial number (opsional) dan kondisi untuk tiap unit barang yang ditambahkan.</p>
                        
                        <div id="dynamicUnitInputsBM" class="space-y-4">
                            <!-- Dynamic inputs inserted by JS -->
                        </div>
                    </div>
                    
                    <!-- Footer Step 2 -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between mt-auto">
                        <button type="button" onclick="prevStepBM()" class="px-5 py-2.5 text-[13px] font-bold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                            Kembali
                        </button>
                        <button type="submit" class="px-5 py-2.5 text-[13px] font-bold text-white bg-[#3F51B5] rounded-lg hover:bg-[#3949AB] transition-colors shadow-sm flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">save</span>
                            Simpan Barang
                        </button>
                    </div>
                </div> <!-- END STEP 2 -->
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
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis Masuk</span>
                    <span id="detail_jenis" class="text-sm text-gray-700">-</span>
                </div>
                <div class="flex justify-between items-start py-2 border-b border-gray-100">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Rentang Peminjaman</span>
                    <span id="detail_rentang" class="text-sm text-gray-700">-</span>
                </div>
                <div class="flex justify-between items-start py-2 border-b border-gray-100">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Biaya Peminjaman</span>
                    <span id="detail_biaya" class="text-sm text-green-600 font-semibold">-</span>
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
        function showDetailModal(id, nama, kode, tanggal, kategori, kondisi, jumlah, catatan, user, jenis, rentang, biaya) {
            document.getElementById('detail_nama').textContent = nama;
            document.getElementById('detail_kode').textContent = kode;
            document.getElementById('detail_tanggal').textContent = tanggal;
            document.getElementById('detail_kategori').textContent = kategori;
            document.getElementById('detail_kondisi').textContent = kondisi;
            document.getElementById('detail_jumlah').textContent = jumlah + ' unit';
            document.getElementById('detail_jenis').textContent = jenis || '-';
            document.getElementById('detail_rentang').textContent = rentang || '-';
            document.getElementById('detail_biaya').textContent = biaya ? 'Rp ' + parseInt(biaya).toLocaleString('id-ID') : '-';
            document.getElementById('detail_catatan').textContent = catatan || '-';
            document.getElementById('detail_user').textContent = user || '-';
            document.getElementById('detailModal').classList.remove('hidden');
        }
        // Close export dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('exportMenu');
            if (menu && !menu.classList.contains('hidden') && !e.target.closest('.relative')) {
                menu.classList.add('hidden');
            }
        });
    </script>

        <script>
        window._itemsConfig = {
            unitsRoute: "{{ route('items.units') }}",
            nextCodeRoute: "{{ route('items.next-code') }}",
            quickCategoryRoute: "{{ route('items.quick-category') }}",
            csrfToken: "{{ csrf_token() }}",
            categoriesData: {!! json_encode($categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'prefix' => $c->prefix, 'last_code_number' => $c->last_code_number])) !!}
        };
        function toggleAddBarangMasukModal(show) {
            const modal = document.getElementById('addBarangMasukModal');
            if (modal) {
                if (show) {
                    // Reset to step 1 on open
                    const step1 = document.getElementById('formStep1BM');
                    const step2 = document.getElementById('formStep2BM');
                    if (step1) step1.classList.remove('hidden');
                    if (step2) step2.classList.add('hidden');
                    modal.classList.remove('hidden');
                } else {
                    modal.classList.add('hidden');
                }
            }
        }
        
        function nextStepBM() {
            const step1 = document.getElementById('formStep1BM');
            const step1Inputs = step1.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;
            for (let input of step1Inputs) {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    isValid = false;
                    break;
                }
            }
            if (!isValid) return;
            
            const form = document.getElementById('addBarangForm');
            const qtyInput = form.querySelector('input[name="quantity"]');
            const qty = parseInt(qtyInput ? qtyInput.value : 1) || 1;
            const container = document.getElementById('dynamicUnitInputsBM');
            
            container.innerHTML = '';
            for (let i = 0; i < qty; i++) {
                container.innerHTML += `
                    <div class="bg-white border border-gray-200 p-4 rounded-xl shadow-sm">
                        <h4 class="text-[13px] font-bold text-gray-800 mb-3 border-b border-gray-100 pb-2">Unit ${i + 1}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Serial Number</label>
                                <input type="text" name="serial_numbers[]" placeholder="Nomor seri unit ${i + 1} (Opsional)" 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Kondisi <span class="text-red-500">*</span></label>
                                <select name="conditions[]" required 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px] bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="baik">Baik</option>
                                    <option value="rusak_ringan">Rusak Ringan</option>
                                    <option value="rusak_berat">Rusak Berat</option>
                                    <option value="hilang">Hilang</option>
                                </select>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            step1.classList.add('hidden');
            document.getElementById('formStep2BM').classList.remove('hidden');
        }
        
        function prevStepBM() {
            document.getElementById('formStep2BM').classList.add('hidden');
            document.getElementById('formStep1BM').classList.remove('hidden');
        }
    </script>

    @vite(['resources/js/turbo-navigation.js', 'resources/js/items-page.js'])
    @include('components.accessibility-button')
</body>
</html>
