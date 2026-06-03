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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nama Barang -->
                        <div class="space-y-1.5">
                            <label class="block text-[13px] font-semibold text-gray-700">Nama Barang <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required placeholder="Contoh: MikroTik RB750Gr3" value="{{ old('name') }}" 
                                class="w-full border rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 placeholder-gray-400 shadow-sm {{ $errors->has('name') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                            @error('name') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Kode Inventaris -->
                        <div class="space-y-1.5">
                            <label class="block text-[13px] font-semibold text-gray-700">Kode Inventaris <span class="text-red-500">*</span></label>
                            <input type="text" name="code" required placeholder="Contoh: INV-00001" value="{{ old('code') }}" 
                                class="w-full border rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 placeholder-gray-400 shadow-sm {{ $errors->has('code') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                            @error('code') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
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

    <script>
        // Modal functions
        window.toggleAddBarangModal = function(show) {
            const modal = document.getElementById('addBarangModal');
            if (modal) {
                if (show) {
                    modal.classList.remove('hidden');
                } else {
                    modal.classList.add('hidden');
                }
            }
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                toggleAddBarangModal(false);
            }
        });

        // Format rupiah helper
        function formatRupiah(value) {
            if (!value) return '';
            let clean = value.toString().replace(/\D/g, '');
            return clean.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        document.addEventListener('DOMContentLoaded', function () {
            const priceInput = document.getElementById('purchase_price_input');
            const addForm = document.getElementById('addBarangForm');

            if (priceInput) {
                if (priceInput.value) {
                    priceInput.value = formatRupiah(priceInput.value);
                }
                priceInput.addEventListener('input', function(e) {
                    e.target.value = formatRupiah(e.target.value);
                });
            }

            if (addForm) {
                addForm.addEventListener('submit', function(e) {
                    if (priceInput) {
                        priceInput.value = priceInput.value.replace(/\./g, '');
                    }
                });
            }

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

    @include('components.accessibility-button')
</body>
</html>



