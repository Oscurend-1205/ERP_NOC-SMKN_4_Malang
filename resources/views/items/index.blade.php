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
                    <a href="{{ route('items.scan-input') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 font-semibold rounded-lg hover:bg-blue-100 transition-all shadow-sm active:scale-95 text-sm border border-blue-200">
                        <span class="material-symbols-outlined text-[18px]">qr_code_scanner</span> 
                        Scan & Tambah
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
                                        <code class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded font-mono font-bold">{{ $item->prefix }}</code>
                                    </td>
                                    <td class="py-4 px-6 font-semibold text-sm text-gray-800">{{ $item->name }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $item->brand ?? '-' }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $item->model ?? '-' }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $item->category->name }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $item->location->name }}</td>
                                    <td class="py-4 px-6 text-sm font-bold text-[#3F51B5] text-center bg-indigo-50/50">{{ $item->total_stock }}</td>
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
                                            <button type="button" onclick="openUnitsModal('{{ $item->name }}', '{{ $item->location_id }}', '{{ $item->condition }}', '{{ $item->status }}', '{{ $item->prefix }}')" class="px-3 py-1.5 text-[#3F51B5] bg-indigo-50 hover:bg-[#3F51B5] hover:text-white rounded-lg transition-colors flex items-center gap-1.5 font-bold text-xs border border-indigo-100 shadow-sm" title="Lihat Daftar Unit">
                                                <span class="material-symbols-outlined text-[16px]">list_alt</span> Rincian Unit
                                            </button>
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

    </main>

    <!-- Modal Tambah Barang (Tailwind Styled) -->
    <div id="addBarangModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 z-[100] flex items-center justify-center p-4">
        <!-- Backdrop Blur -->
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="toggleAddBarangModal(false)"></div>
        
        <!-- Modal Content -->
        <div class="relative w-full max-w-[975px] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] font-sans">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-white">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Tambah Barang Baru</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Daftarkan barang elektronik baru ke inventaris</p>
                </div>
                <button onclick="toggleAddBarangModal(false)" class="text-gray-400 hover:text-gray-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <!-- Form Body -->
            <form id="addBarangForm" action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <div class="px-6 py-5 space-y-4 overflow-y-auto">
                    <!-- Tipe Input Toggle -->
                    <div class="mb-4 bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <label class="block text-[13px] font-semibold text-gray-700 mb-2">Tipe Input Barang</label>
                        <div class="flex items-center space-x-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="item_type" value="new" checked class="form-radio h-4 w-4 text-[#3F51B5] focus:ring-[#3F51B5] border-gray-300">
                                <span class="ml-2 text-sm text-gray-700 font-medium">Barang Baru</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="item_type" value="existing" class="form-radio h-4 w-4 text-[#3F51B5] focus:ring-[#3F51B5] border-gray-300">
                                <span class="ml-2 text-sm text-gray-700 font-medium">Barang Sudah Ada</span>
                            </label>
                        </div>
                    </div>

                    <!-- Pilih Barang (Existing) -->
                    <div id="existing_item_selector" class="mb-4 hidden">
                        <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Pilih Barang yang Sudah Ada <span class="text-red-500">*</span></label>
                        <select id="existing_item_id" class="w-full border rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-gray-700 bg-white shadow-sm cursor-pointer">
                            <option value="">-- Pilih Barang --</option>
                            @if(isset($existingItems))
                                @foreach($existingItems as $existing)
                                    <option value="{{ $existing->name }}" data-brand="{{ $existing->brand }}" data-model="{{ $existing->model }}" data-category="{{ $existing->category_id }}">
                                        {{ $existing->name }} {{ $existing->brand ? ' - '.$existing->brand : '' }} {{ $existing->model ? ' ('.$existing->model.')' : '' }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <p class="text-[11px] text-gray-500 mt-1.5">Merek, Model, dan Kategori akan terisi otomatis sesuai barang yang dipilih. Kode Inventaris akan di-generate berurutan otomatis.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nama Barang -->
                        <div class="space-y-1.5">
                            <label class="block text-[13px] font-semibold text-gray-700">Nama Barang <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required placeholder="Contoh: MikroTik RB750Gr3" value="{{ old('name') }}" 
                                class="w-full border rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 placeholder-gray-400 shadow-sm {{ $errors->has('name') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                            @error('name') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Kode Inventaris -->
                        <div class="space-y-1.5 hidden">
                            <label class="block text-[13px] font-semibold text-gray-700">Kode Inventaris <span class="text-red-500">*</span></label>
                            <input type="hidden" name="code" value="AUTO-GENERATED">
                        </div>

                        <!-- Serial Number -->
                        <div class="space-y-1.5">
                            <label class="block text-[13px] font-semibold text-gray-700">Serial Number</label>
                            <input type="text" name="serial_number" placeholder="Nomor seri produk" value="{{ old('serial_number') }}" 
                                class="w-full border rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 placeholder-gray-400 shadow-sm {{ $errors->has('serial_number') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                            @error('serial_number') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Merek -->
                        <div class="space-y-1.5">
                            <label class="block text-[13px] font-semibold text-gray-700">Merek</label>
                            <input type="text" name="brand" placeholder="Contoh: Cisco, MikroTik" value="{{ old('brand') }}" 
                                class="w-full border rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 placeholder-gray-400 shadow-sm {{ $errors->has('brand') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                            @error('brand') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Model -->
                        <div class="space-y-1.5">
                            <label class="block text-[13px] font-semibold text-gray-700">Model</label>
                            <input type="text" name="model" placeholder="Model perangkat" value="{{ old('model') }}" 
                                class="w-full border rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 placeholder-gray-400 shadow-sm {{ $errors->has('model') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                            @error('model') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Kategori -->
                        <div class="space-y-1.5">
                            <label class="block text-[13px] font-semibold text-gray-700">Kategori <span class="text-red-500">*</span></label>
                            <select name="category_id" required 
                                class="w-full border rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 text-gray-700 bg-white shadow-sm cursor-pointer {{ $errors->has('category_id') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Lokasi -->
                        <div class="space-y-1.5">
                            <label class="block text-[13px] font-semibold text-gray-700">Lokasi <span class="text-red-500">*</span></label>
                            <select name="location_id" required 
                                class="w-full border rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 text-gray-700 bg-white shadow-sm cursor-pointer {{ $errors->has('location_id') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                                <option value="">Pilih Lokasi</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                            @error('location_id') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Jumlah -->
                        <div class="space-y-1.5">
                            <label class="block text-[13px] font-semibold text-gray-700">Jumlah <span class="text-red-500">*</span></label>
                            <input type="number" name="quantity" required min="1" value="{{ old('quantity', 1) }}" 
                                class="w-full border rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 shadow-sm {{ $errors->has('quantity') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                            @error('quantity') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Kondisi -->
                        <div class="space-y-1.5">
                            <label class="block text-[13px] font-semibold text-gray-700">Kondisi <span class="text-red-500">*</span></label>
                            <select name="condition" required 
                                class="w-full border rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 text-gray-700 bg-white shadow-sm cursor-pointer {{ $errors->has('condition') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                                <option value="baik" {{ old('condition', 'baik') == 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="rusak_ringan" {{ old('condition') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                <option value="rusak_berat" {{ old('condition') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                <option value="hilang" {{ old('condition') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                            </select>
                            @error('condition') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Status -->
                        <div class="space-y-1.5">
                            <label class="block text-[13px] font-semibold text-gray-700">Status <span class="text-red-500">*</span></label>
                            <select name="status" required 
                                class="w-full border rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 text-gray-700 bg-white shadow-sm cursor-pointer {{ $errors->has('status') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                                <option value="tersedia" {{ old('status', 'tersedia') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="dipinjam" {{ old('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="dimusnahkan" {{ old('status') == 'dimusnahkan' ? 'selected' : '' }}>Dimusnahkan</option>
                            </select>
                            @error('status') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Tanggal Pembelian -->
                        <div class="space-y-1.5">
                            <label class="block text-[13px] font-semibold text-gray-700">Tanggal Pembelian</label>
                            <input type="date" name="purchase_date" value="{{ old('purchase_date') }}" 
                                class="w-full border rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 text-gray-700 bg-white shadow-sm {{ $errors->has('purchase_date') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                            @error('purchase_date') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Harga Beli -->
                        <div class="space-y-1.5">
                            <label class="block text-[13px] font-semibold text-gray-700">Harga Beli</label>
                            <div class="relative flex items-center rounded-lg border shadow-sm focus-within:ring-1 bg-white overflow-hidden {{ $errors->has('purchase_price') ? 'border-red-500 focus-within:ring-red-500 focus-within:border-red-500' : 'border-gray-300 focus-within:ring-blue-500 focus-within:border-blue-500' }}">
                                <span class="bg-gray-50 px-3 py-2 text-[13px] text-gray-500 border-r border-gray-200 select-none">Rp</span>
                                <input type="text" name="purchase_price" id="purchase_price_input" placeholder="0" value="{{ old('purchase_price') }}" 
                                    class="w-full border-0 pl-3 pr-1 py-2 text-[13px] focus:ring-0 focus:outline-none placeholder-gray-400">
                                <span class="text-[13px] text-gray-500 pr-3 select-none">,00</span>
                            </div>
                            @error('purchase_price') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Catatan (Full Width) -->
                    <div class="space-y-1.5">
                        <label class="block text-[13px] font-semibold text-gray-700">Catatan</label>
                        <textarea name="notes" placeholder="Catatan tambahan (opsional)" 
                            class="w-full border rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 placeholder-gray-400 shadow-sm h-20 {{ $errors->has('notes') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">{{ old('notes') }}</textarea>
                        @error('notes') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                    </div>

                    <!-- Foto Barang (Full Width) -->
                    <div class="space-y-1.5">
                        <label class="block text-[13px] font-semibold text-gray-700">Foto Barang</label>
                        <input type="file" name="image" accept="image/jpeg,image/png,image/jpg" 
                            class="w-full border rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 bg-white text-gray-700 shadow-sm {{ $errors->has('image') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                        <div class="text-[11px] text-gray-400 mt-1">Format: JPG, PNG. Maks: 2MB</div>
                        @error('image') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3 mt-auto">
                    <button type="button" onclick="toggleAddBarangModal(false)" class="px-5 py-2 text-[13px] font-bold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 text-[13px] font-bold text-white bg-[#3F51B5] rounded-lg hover:bg-[#3949AB] transition-colors shadow-sm">
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
        <div class="relative w-full max-w-[800px] bg-white rounded-2xl shadow-2xl flex flex-col font-sans max-h-[90vh] overflow-hidden">
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
            <div class="px-6 py-6 overflow-y-auto bg-gray-50 flex-1 rounded-b-2xl">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-12 text-center">No</th>
                                <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kode Spesifik</th>
                                <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Cetak QR & Kelola</th>
                            </tr>
                        </thead>
                        <tbody id="unitsTableBody" class="divide-y divide-gray-100">
                            <!-- Injected via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Pass Blade-injected values to the external JS module
        window._itemsConfig = {
            unitsRoute: "{{ route('items.units') }}",
            csrfToken: "{{ csrf_token() }}"
        };
    </script>
    </div> <!-- END PJAX CONTENT -->
    @vite(['resources/js/turbo-navigation.js', 'resources/js/items-page.js'])
    @include('components.accessibility-button')
</body>
</html>



