@extends('layouts.app')

@section('title', 'Buat Stok Opname')

@section('content')
<div class="mb-6">
    <a href="{{ route('stock-take.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-[#3F51B5] transition-colors mb-3">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
        Kembali ke Stok Opname
    </a>
    <h2 class="text-2xl font-bold text-gray-800">Buat Sesi Stok Opname</h2>
    <p class="text-sm text-gray-500 mt-1">Pilih lokasi dan mulai verifikasi fisik aset</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Informasi Sesi</h3>
            </div>
            <form method="POST" action="{{ route('stock-take.store') }}" class="p-6 space-y-5">
                @csrf

                <!-- Judul -->
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Sesi <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                        placeholder="Contoh: Stok Opname Semester 1 2026"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                    @error('title')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi / Catatan</label>
                    <textarea name="description" id="description" rows="3" placeholder="Catatan opsional tentang sesi opname ini..."
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors resize-none">{{ old('description') }}</textarea>
                </div>

                <!-- Lokasi -->
                <div>
                    <label for="location_id" class="block text-sm font-semibold text-gray-700 mb-1.5">Lokasi / Ruangan</label>
                    <select name="location_id" id="location_id"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white"
                        onchange="updatePreview(this.value)">
                        <option value="">Semua Lokasi ({{ $totalItems }} item)</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                {{ $location->name }} ({{ $locationItemCounts[$location->id] ?? 0 }} item)
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Pilih lokasi spesifik atau biarkan kosong untuk semua lokasi</p>
                </div>

                <!-- Submit -->
                <div class="pt-2 flex gap-3">
                    <button type="submit" class="px-6 py-2.5 bg-[#3F51B5] text-white text-sm font-semibold rounded-xl hover:bg-[#3949AB] transition-colors shadow-sm inline-flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">play_arrow</span>
                        Buat Sesi
                    </button>
                    <a href="{{ route('stock-take.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Preview Card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden sticky top-24">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Preview</h3>
            </div>
            <div class="p-6">
                <div class="text-center">
                    <div class="w-16 h-16 rounded-2xl bg-blue-100 flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-[32px] text-[#3F51B5]">fact_check</span>
                    </div>
                    <div id="previewCount" class="text-3xl font-extrabold text-gray-800">{{ $totalItems }}</div>
                    <div class="text-sm text-gray-500 mt-1">item akan diopname</div>
                </div>

                <div class="mt-6 space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Kode Otomatis</span>
                        <span class="font-semibold text-gray-800">SO-{{ now()->year }}-XXX</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Status Awal</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700">Draft</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Petugas</span>
                        <span class="font-semibold text-gray-800">{{ auth()->user()->name }}</span>
                    </div>
                </div>

                <div class="mt-6 p-3 bg-blue-50 rounded-xl">
                    <div class="flex gap-2">
                        <span class="material-symbols-outlined text-[18px] text-blue-600 flex-shrink-0 mt-0.5">info</span>
                        <p class="text-xs text-blue-700">Semua item dari lokasi terpilih akan otomatis dimuat ke sesi ini. Anda bisa mengisi jumlah aktual satu per satu.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const locationCounts = @json($locationItemCounts);
    const totalItems = {{ $totalItems }};

    function updatePreview(locationId) {
        const countEl = document.getElementById('previewCount');
        if (locationId && locationCounts[locationId] !== undefined) {
            countEl.textContent = locationCounts[locationId];
        } else {
            countEl.textContent = totalItems;
        }
    }
</script>
@endsection
