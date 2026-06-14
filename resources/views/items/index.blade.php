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
    <main class="grow flex flex-col h-screen overflow-y-auto transition-all duration-300 w-full min-w-0">
        @include('partials.topbar')

        <!-- BEGIN: Page Content -->
        <div id="pjax-content" class="p-4 md:p-10 pt-4 md:pt-6 space-y-6">
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
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('items.barang-masuk') }}" class="flex items-center gap-2 px-4 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition-all shadow-sm active:scale-95 text-sm">
                        <span class="material-symbols-outlined text-[18px]">south_east</span>
                        Barang Masuk
                    </a>
                    <a href="{{ route('items.barang-keluar') }}" class="flex items-center gap-2 px-4 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition-all shadow-sm active:scale-95 text-sm">
                        <span class="material-symbols-outlined text-[18px]">north_west</span>
                        Barang Keluar
                    </a>
                    <button type="button" onclick="toggleAddBarangModal(true)" class="flex items-center gap-2 px-4 py-2 bg-[#3F51B5] text-white font-semibold rounded-lg hover:bg-[#3949AB] transition-all shadow-sm active:scale-95 text-sm">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        Tambah Barang
                    </button>
                </div>
            </div>

            {{-- Filter Bar --}}
            <form id="filterForm" action="{{ route('items.index') }}" method="GET" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-wrap items-center gap-3">
                <div class="relative grow min-w-50">
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
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Model</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Qty</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($items as $i => $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-6 text-sm text-gray-500 text-center">{{ $items->firstItem() + $i }}</td>
                                    <td class="py-4 px-6">
                                        <code class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded font-mono font-bold">{{ $item->prefix }}</code>
                                    </td>
                                    <td class="py-4 px-6 font-semibold text-sm text-gray-800">{{ $item->name }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $item->brand ?? '-' }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $item->model ?? '-' }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $item->category->name }}</td>
                                    <td class="py-4 px-6 text-sm font-bold text-[#3F51B5] text-center bg-indigo-50/50">{{ $item->total_stock }}</td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" onclick="openUnitsModal('{{ $item->name }}', '{{ $item->brand }}', '{{ $item->model }}', '{{ $item->category_id }}', '{{ $item->sub_prefix }}')" class="px-3 py-1.5 text-[#3F51B5] bg-indigo-50 hover:bg-[#3F51B5] hover:text-white rounded-lg transition-colors flex items-center gap-1.5 font-bold text-xs border border-indigo-100 shadow-sm" title="Lihat Daftar Unit">
                                                <span class="material-symbols-outlined text-[16px]">list_alt</span> Rincian Unit
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-24 text-center text-gray-400">
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

    </main>

    <!-- Modal Tambah Barang Baru -->
    <div id="addBarangModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="toggleAddBarangModal(false)"></div>
        
        <div class="relative w-full max-w-[1000px] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[92vh] font-sans">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#3F51B5] to-[#5C6BC0]">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-[22px]">add_box</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white">Tambah Barang Baru</h2>
                        <p class="text-xs text-white/70 mt-0.5">Daftarkan barang elektronik baru ke inventaris</p>
                    </div>
                </div>
                <button onclick="toggleAddBarangModal(false)" class="text-white/70 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <form id="addBarangForm" action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <div class="px-6 py-5 space-y-5 overflow-y-auto flex-1">

                    {{-- Tipe Input Toggle --}}
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-200">
                        <div class="flex items-center gap-4">
                            <label class="flex items-center cursor-pointer gap-2">
                                <input type="radio" name="item_type" value="new" checked class="form-radio h-4 w-4 text-[#3F51B5] focus:ring-[#3F51B5] border-gray-300">
                                <span class="text-sm text-gray-700 font-medium">Barang Baru</span>
                            </label>
                            <label class="flex items-center cursor-pointer gap-2">
                                <input type="radio" name="item_type" value="existing" class="form-radio h-4 w-4 text-[#3F51B5] focus:ring-[#3F51B5] border-gray-300">
                                <span class="text-sm text-gray-700 font-medium">Barang Sudah Ada</span>
                            </label>
                        </div>
                    </div>

                    {{-- Existing Item Selector (hidden by default) --}}
                    <div id="existing_item_selector" class="hidden">
                        <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Pilih Barang yang Sudah Ada <span class="text-red-500">*</span></label>
                        <select id="existing_item_id" class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-gray-700 bg-white shadow-sm cursor-pointer">
                            <option value="">-- Pilih Barang --</option>
                            @if(isset($existingItems))
                                @foreach($existingItems as $existing)
                                    <option value="{{ $existing->name }}" data-brand="{{ $existing->brand }}" data-model="{{ $existing->model }}" data-category="{{ $existing->category_id }}" data-sub-prefix="{{ $existing->sub_prefix }}">
                                        {{ $existing->name }}{{ $existing->brand ? ' - '.$existing->brand : '' }}{{ $existing->model ? ' ('.$existing->model.')' : '' }}{{ $existing->sub_prefix ? ' ['.$existing->sub_prefix.']' : '' }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <p class="text-[11px] text-gray-500 mt-1.5">Merek, Model, dan Kategori akan terisi otomatis. Kode Inventaris di-generate berurutan.</p>
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

                            {{-- Serial Number --}}
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Serial Number</label>
                                <input type="text" name="serial_number" placeholder="Nomor seri perangkat" value="{{ old('serial_number') }}" 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 placeholder-gray-400 shadow-sm {{ $errors->has('serial_number') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                                @error('serial_number') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
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
                                <label class="block text-[13px] font-semibold text-gray-700">Lokasi <span class="text-red-500">*</span></label>
                                <select name="location_id" required 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 text-gray-700 bg-white shadow-sm cursor-pointer {{ $errors->has('location_id') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                                    <option value="">Pilih Lokasi</option>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                                    @endforeach
                                </select>
                                @error('location_id') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Jumlah --}}
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Jumlah Unit <span class="text-red-500">*</span></label>
                                <input type="number" name="quantity" required min="1" value="{{ old('quantity', 1) }}" 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 shadow-sm {{ $errors->has('quantity') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                                <p class="text-[11px] text-gray-400">Setiap unit akan mendapat kode inventaris unik</p>
                                @error('quantity') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Kondisi --}}
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Kondisi <span class="text-red-500">*</span></label>
                                <select name="condition" required 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 text-gray-700 bg-white shadow-sm cursor-pointer {{ $errors->has('condition') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                                    <option value="baik" {{ old('condition', 'baik') == 'baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="rusak_ringan" {{ old('condition') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                    <option value="rusak_berat" {{ old('condition') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                    <option value="hilang" {{ old('condition') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                                </select>
                                @error('condition') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
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

                            {{-- Tanggal Pembelian --}}
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Tanggal Pembelian</label>
                                <input type="date" name="purchase_date" value="{{ old('purchase_date') }}" 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 text-gray-700 bg-white shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                @error('purchase_date') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Harga Beli --}}
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="block text-[13px] font-semibold text-gray-700">Harga Beli (per unit)</label>
                                <div class="relative flex items-center rounded-lg border shadow-sm focus-within:ring-1 bg-white overflow-hidden border-gray-300 focus-within:ring-blue-500 focus-within:border-blue-500">
                                    <span class="bg-gray-50 px-3 py-2.5 text-[13px] text-gray-500 border-r border-gray-200 select-none font-semibold">Rp</span>
                                    <input type="text" name="purchase_price" id="purchase_price_input" placeholder="0" value="{{ old('purchase_price') }}" 
                                        class="w-full border-0 pl-3 pr-1 py-2.5 text-[13px] focus:ring-0 focus:outline-none placeholder-gray-400">
                                    <span class="text-[13px] text-gray-500 pr-3 select-none">,00</span>
                                </div>
                                @error('purchase_price') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
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

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3 mt-auto">
                    <button type="button" onclick="toggleAddBarangModal(false)" class="px-5 py-2.5 text-[13px] font-bold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-[13px] font-bold text-white bg-[#3F51B5] rounded-lg hover:bg-[#3949AB] transition-colors shadow-sm flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">save</span>
                        Simpan Barang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal QR Code Barang -->
    <div id="qrCodeModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
        <!-- Backdrop Blur -->
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="closeQrModal()"></div>
        
        <!-- Modal Content -->
        <div class="relative w-full max-w-[400px] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col font-sans">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-white">
                <div class="overflow-hidden">
                    <h2 class="text-lg font-bold text-gray-900">QR Code Barang</h2>
                    <p id="qrModalSubtitle" class="text-xs text-gray-500 mt-0.5 truncate max-w-[280px]">Generate QR Code</p>
                </div>
                <button onclick="closeQrModal()" class="text-gray-400 hover:text-gray-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 flex-shrink-0">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <!-- Form Body -->
            <div class="px-6 py-8 flex flex-col items-center justify-center bg-gray-50/50">
                <!-- Wrapper id untuk div QR Code -->
                <div id="itemQrContainer" class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-4 flex items-center justify-center min-w-[232px] min-h-[232px]">
                    <!-- QR akan di-render di sini -->
                </div>
                <div id="qrCodeText" class="text-lg font-bold text-[#3F51B5] font-mono tracking-widest bg-[#E8EAF6] px-4 py-1.5 rounded-lg mb-2"></div>
                <p class="text-[11px] text-gray-500 text-center px-4 mt-2">Cetak atau download QR ini dan tempelkan pada fisik barang untuk discan saat peminjaman.</p>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-white border-t border-gray-100 flex items-center justify-center gap-3">
                <button type="button" onclick="downloadQrCode()" class="w-full flex justify-center items-center gap-2 px-5 py-2.5 text-[14px] font-bold text-white bg-[#3F51B5] rounded-xl hover:bg-[#3949AB] transition-colors shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-[#3F51B5]">
                    <span class="material-symbols-outlined text-[20px]">download</span>
                    Download QR Code
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Rincian Unit -->
    <div id="unitsModal" class="hidden fixed inset-0 z-[90] flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="closeUnitsModal()"></div>
        
        <!-- Modal Content -->
        <div class="relative w-full max-w-[1050px] bg-white rounded-2xl shadow-2xl flex flex-col font-sans max-h-[90vh] overflow-hidden">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-white rounded-t-2xl">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Daftar Unit Barang Spesifik</h2>
                    <p id="unitsModalSubtitle" class="text-xs text-gray-500 mt-0.5">Memuat...</p>
                </div>
                <button onclick="closeUnitsModal()" class="text-gray-400 hover:text-gray-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 bg-gray-50">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <!-- Body -->
            <div class="px-5 py-5 overflow-y-auto bg-gray-50 flex-1 rounded-b-2xl">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 border-b border-gray-200">
                                <th class="py-2.5 px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider w-10 text-center">No</th>
                                <th class="py-2.5 px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Kode Spesifik</th>
                                <th class="py-2.5 px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Serial Number</th>
                                <th class="py-2.5 px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider text-center">Kondisi</th>
                                <th class="py-2.5 px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider text-center">Status</th>
                                <th class="py-2.5 px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider text-center">Lokasi</th>
                                <th class="py-2.5 px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider text-center">Tgl Beli</th>
                                <th class="py-2.5 px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider text-center w-28">Kelola</th>
                            </tr>
                        </thead>
                        <tbody id="unitsTableBody" class="divide-y divide-gray-100">
                            <tr><td colspan="8" class="text-center py-6 text-gray-400 text-sm">Pilih barang untuk melihat daftar unit</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Quick Add Kategori -->
    <div id="quickCategoryModal" class="hidden fixed inset-0 z-[110] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeQuickCategoryModal()"></div>
        <div class="relative w-full max-w-[420px] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col font-sans">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h2 class="text-base font-bold text-gray-900">Tambah Kategori Baru</h2>
                    <p class="text-[11px] text-gray-500 mt-0.5">Buat kategori dan prefix langsung dari sini</p>
                </div>
                <button onclick="closeQuickCategoryModal()" class="text-gray-400 hover:text-gray-600 w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            <form id="quickCategoryForm" class="px-6 py-5 space-y-4">
                <div class="space-y-1.5">
                    <label class="block text-[13px] font-semibold text-gray-700">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" id="qc_name" required placeholder="Contoh: Router, Switch, Kabel" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px] focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none shadow-sm">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[13px] font-semibold text-gray-700">Prefix Kode <span class="text-red-500">*</span></label>
                    <input type="text" id="qc_prefix" required maxlength="10" placeholder="Contoh: RTR, SWT, KBL" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px] font-mono font-bold uppercase focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none shadow-sm" style="text-transform:uppercase">
                    <p class="text-[11px] text-gray-400">Awalan kode barang (maks 10 karakter, akan otomatis kapital)</p>
                </div>
                <div id="qc_error" class="hidden bg-red-50 text-red-600 text-xs p-2.5 rounded-lg border border-red-200"></div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeQuickCategoryModal()" class="px-4 py-2 text-[13px] font-semibold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">Batal</button>
                    <button type="submit" id="qc_submit" class="px-4 py-2 text-[13px] font-bold text-white bg-[#3F51B5] rounded-lg hover:bg-[#3949AB] shadow-sm flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">save</span> Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Pass Blade-injected values to the external JS module
        window._itemsConfig = {
            unitsRoute: "{{ route('items.units') }}",
            nextCodeRoute: "{{ route('items.next-code') }}",
            quickCategoryRoute: "{{ route('items.quick-category') }}",
            csrfToken: "{{ csrf_token() }}",
            categoriesData: {!! json_encode($categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'prefix' => $c->prefix, 'last_code_number' => $c->last_code_number])) !!}
        };
    </script>
    </div> <!-- END PJAX CONTENT -->
    @vite(['resources/js/turbo-navigation.js', 'resources/js/items-page.js'])
    @include('components.accessibility-button')
</body>
</html>



