@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
<!-- BEGIN: Page Header -->
<div class="flex justify-between items-start mb-6" data-purpose="page-title-section">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Edit Barang</h1>
        <p class="text-sm text-slate-500 mt-1">Perbarui data: <span class="font-bold text-blue-600">{{ $item->name }}</span> ({{ $item->code }})</p>
    </div>
    <a href="{{ route('items.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 rounded-lg text-sm font-semibold flex items-center shadow-sm transition-all border border-slate-200">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Kembali
    </a>
</div>
<!-- END: Page Header -->

<form action="{{ route('items.update', $item) }}" method="POST" enctype="multipart/form-data" id="editBarangForm">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Panel: General Information Form (2/3 width) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-6">
                
                <!-- Section Title -->
                <div>
                    <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3">Informasi Utama</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nama Barang -->
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700" for="name">Nama Barang <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" required placeholder="Contoh: MikroTik RB750Gr3" value="{{ old('name', $item->name) }}" 
                            class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400 shadow-sm transition-all">
                        @error('name') <div class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</div> @enderror
                    </div>

                    <!-- Kode Inventaris -->
                    <div class="space-y-1.5">
                        <label class="block text-sm font-bold text-slate-700" for="code">Kode Inventaris <span class="text-red-500">*</span></label>
                        <input type="text" name="code" id="code" required placeholder="Contoh: INV-00001" value="{{ old('code', $item->code) }}" 
                            class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400 shadow-sm transition-all font-mono">
                        @error('code') <div class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</div> @enderror
                    </div>

                    <!-- Serial Number -->
                    <div class="space-y-1.5">
                        <label class="block text-sm font-bold text-slate-700" for="serial_number">Serial Number</label>
                        <input type="text" name="serial_number" id="serial_number" placeholder="Nomor seri produk" value="{{ old('serial_number', $item->serial_number) }}" 
                            class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400 shadow-sm transition-all">
                        @error('serial_number') <div class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</div> @enderror
                    </div>

                    <!-- Merek -->
                    <div class="space-y-1.5">
                        <label class="block text-sm font-bold text-slate-700" for="brand">Merek</label>
                        <input type="text" name="brand" id="brand" placeholder="Contoh: Cisco, MikroTik" value="{{ old('brand', $item->brand) }}" 
                            class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400 shadow-sm transition-all">
                        @error('brand') <div class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</div> @enderror
                    </div>

                    <!-- Model -->
                    <div class="space-y-1.5">
                        <label class="block text-sm font-bold text-slate-700" for="model">Model</label>
                        <input type="text" name="model" id="model" placeholder="Model perangkat" value="{{ old('model', $item->model) }}" 
                            class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400 shadow-sm transition-all">
                        @error('model') <div class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</div> @enderror
                    </div>

                    <!-- Kategori -->
                    <div class="space-y-1.5">
                        <label class="block text-sm font-bold text-slate-700" for="category_id">Kategori <span class="text-red-500">*</span></label>
                        <select name="category_id" id="category_id" required 
                            class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-700 bg-white shadow-sm cursor-pointer transition-all">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $item->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</div> @enderror
                    </div>

                    <!-- Lokasi -->
                    <div class="space-y-1.5">
                        <label class="block text-sm font-bold text-slate-700" for="location_id">Lokasi <span class="text-red-500">*</span></label>
                        <select name="location_id" id="location_id" required 
                            class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-700 bg-white shadow-sm cursor-pointer transition-all">
                            <option value="">Pilih Lokasi</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ old('location_id', $item->location_id) == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                            @endforeach
                        </select>
                        @error('location_id') <div class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <!-- Card for Stock & Financial Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-6">
                <!-- Section Title -->
                <div>
                    <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3">Status & Keuangan</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Jumlah -->
                    <div class="space-y-1.5">
                        <label class="block text-sm font-bold text-slate-700" for="quantity">Jumlah <span class="text-red-500">*</span></label>
                        <input type="number" name="quantity" id="quantity" required min="1" value="{{ old('quantity', $item->quantity) }}" 
                            class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all">
                        @error('quantity') <div class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</div> @enderror
                    </div>

                    <!-- Kondisi -->
                    <div class="space-y-1.5">
                        <label class="block text-sm font-bold text-slate-700" for="condition">Kondisi <span class="text-red-500">*</span></label>
                        <select name="condition" id="condition" required 
                            class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-700 bg-white shadow-sm cursor-pointer transition-all">
                            <option value="baik" {{ old('condition', $item->condition) == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak_ringan" {{ old('condition', $item->condition) == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="rusak_berat" {{ old('condition', $item->condition) == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                            <option value="hilang" {{ old('condition', $item->condition) == 'hilang' ? 'selected' : '' }}>Hilang</option>
                        </select>
                        @error('condition') <div class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</div> @enderror
                    </div>

                    <!-- Status -->
                    <div class="space-y-1.5">
                        <label class="block text-sm font-bold text-slate-700" for="status">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" required 
                            class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-700 bg-white shadow-sm cursor-pointer transition-all">
                            <option value="tersedia" {{ old('status', $item->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="dipinjam" {{ old('status', $item->status) == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="maintenance" {{ old('status', $item->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="dimusnahkan" {{ old('status', $item->status) == 'dimusnahkan' ? 'selected' : '' }}>Dimusnahkan</option>
                        </select>
                        @error('status') <div class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</div> @enderror
                    </div>

                    <!-- Tanggal Pembelian -->
                    <div class="space-y-1.5 md:col-span-1">
                        <label class="block text-sm font-bold text-slate-700" for="purchase_date">Tanggal Pembelian</label>
                        <input type="date" name="purchase_date" id="purchase_date" value="{{ old('purchase_date', $item->purchase_date?->format('Y-m-d')) }}" 
                            class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-700 bg-white shadow-sm transition-all">
                        @error('purchase_date') <div class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</div> @enderror
                    </div>

                    <!-- Harga Beli -->
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700" for="purchase_price_input">Harga Beli</label>
                        <div class="relative flex items-center rounded-lg border border-slate-300 shadow-sm focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 bg-white overflow-hidden transition-all">
                            <span class="bg-slate-50 px-3 py-2.5 text-sm text-slate-500 border-r border-slate-200 select-none">Rp</span>
                            <input type="text" name="purchase_price" id="purchase_price_input" placeholder="0" value="{{ old('purchase_price', $item->purchase_price) }}" 
                                class="w-full border-0 pl-3 pr-1 py-2.5 text-sm focus:ring-0 focus:outline-none placeholder-slate-400">
                            <span class="text-sm text-slate-500 pr-3 select-none">,00</span>
                        </div>
                        @error('purchase_price') <div class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel: Image Upload & Notes (1/3 width) -->
        <div class="space-y-6">
            <!-- Card for Photo upload -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-700 border-b border-slate-100 pb-2">Foto Barang</h3>
                </div>

                <div class="space-y-1.5">
                    <div class="border-2 border-dashed border-slate-300 hover:border-blue-500 rounded-xl p-4 text-center cursor-pointer transition-all relative overflow-hidden bg-slate-50/50 hover:bg-slate-50" onclick="document.getElementById('image').click()">
                        <input type="file" name="image" id="image" class="hidden" accept="image/jpeg,image/png,image/jpg" onchange="previewImage(this)">
                        
                        <!-- Placeholder State (No Image Chosen & No Existing Image) -->
                        <div id="image-placeholder" class="space-y-2 {{ $item->image ? 'hidden' : '' }}">
                            <i data-lucide="image" class="w-10 h-10 text-slate-400 mx-auto"></i>
                            <div class="text-xs text-slate-600 font-semibold">Upload Foto Barang</div>
                            <div class="text-[10px] text-slate-400">Format: JPG, PNG. Max: 2MB</div>
                        </div>
                        
                        <!-- Preview State -->
                        <div id="image-preview-container" class="{{ $item->image ? '' : 'hidden' }} space-y-2">
                            <img id="image-preview" src="{{ $item->image ? Storage::url($item->image) : '#' }}" alt="Preview Foto" class="max-h-48 mx-auto rounded-lg object-contain shadow-sm border border-slate-200">
                            <div class="text-xs text-blue-600 hover:text-blue-700 font-semibold flex items-center justify-center gap-1.5 mt-2">
                                <i data-lucide="upload" class="w-3.5 h-3.5"></i> Ganti Foto
                            </div>
                        </div>
                    </div>
                    @if($item->image)
                        <div class="text-[11px] text-slate-400 text-center mt-1">Kosongkan jika tidak ingin mengganti foto</div>
                    @endif
                    @error('image') <div class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Card for Notes -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-700 border-b border-slate-100 pb-2">Catatan Tambahan</h3>
                </div>

                <div class="space-y-1.5">
                    <textarea name="notes" id="notes" rows="4" class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400 shadow-sm resize-none" placeholder="Masukkan catatan tambahan mengenai kondisi, garansi, atau detail lainnya...">{{ old('notes', $item->notes) }}</textarea>
                    @error('notes') <div class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-end gap-3 mt-6 border-t border-slate-200 pt-6">
        <a href="{{ route('items.index') }}" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors shadow-sm">
            Batal
        </a>
        <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors shadow-sm flex items-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i> Perbarui Barang
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
(function() {
    'use strict';

    // Image Preview Helper
    window.previewImage = function(input) {
        const placeholder = document.getElementById('image-placeholder');
        const container = document.getElementById('image-preview-container');
        const preview = document.getElementById('image-preview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                if (placeholder) placeholder.classList.add('hidden');
                if (container) container.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    };

    // Rupiah Formatter Helper
    function formatRupiah(value) {
        if (!value) return '';
        let clean = value.toString().replace(/\D/g, '');
        return clean.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    document.addEventListener('DOMContentLoaded', function() {
        const priceInput = document.getElementById('purchase_price_input');
        const form = document.getElementById('editBarangForm');

        // Initial Formatting
        if (priceInput && priceInput.value) {
            priceInput.value = formatRupiah(priceInput.value);
        }

        // Live formatting on input
        if (priceInput) {
            priceInput.addEventListener('input', function(e) {
                e.target.value = formatRupiah(e.target.value);
            });
        }

        // Clean dots before submit
        if (form) {
            form.addEventListener('submit', function() {
                if (priceInput) {
                    priceInput.value = priceInput.value.replace(/\./g, '');
                }
            });
        }

        // Initialize Lucide icons just in case PJAX navigation didn't trigger it
        if (typeof lucide !== 'undefined' && lucide.createIcons) {
            lucide.createIcons();
        }
    });
})();
</script>
@endpush
