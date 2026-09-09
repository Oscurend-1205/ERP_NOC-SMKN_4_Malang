@extends('layouts.app')

@section('title', 'Buat Pengajuan Pengadaan Alat')

@section('content')
<!-- Header & Navigation -->
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <div class="flex items-center gap-2">
            <a href="{{ route('procurements.index') }}" class="p-1.5 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Form Usulan Pengadaan Alat Baru</h2>
        </div>
        <p class="text-sm text-gray-500 mt-1 ml-9">Silakan lengkapi informasi usulan alat laboratorium dan rincian anggarannya</p>
    </div>
</div>

@if($errors->any())
<div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-start gap-3 text-red-700 text-sm">
    <span class="material-symbols-outlined text-red-500 text-[22px] flex-shrink-0">error</span>
    <div>
        <h4 class="font-bold">Terdapat kesalahan pengisian formulir:</h4>
        <ul class="list-disc list-inside mt-1 space-y-0.5 text-xs">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<form action="{{ route('procurements.store') }}" method="POST" enctype="multipart/form-data" id="procurementForm" class="space-y-6">
    @csrf
    <input type="hidden" name="action_type" id="actionTypeInput" value="submit">

    <!-- Section 1: Informasi Umum -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-5">
        <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
            <span class="material-symbols-outlined text-[#3F51B5] text-[22px]">info</span>
            <h3 class="text-base font-bold text-gray-800">1. Informasi Pengajuan & Unit Pemohon</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Judul Pengadaan -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                    Judul Pengadaan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       placeholder="Contoh: Pengadaan Switch Managed 24-Port & Access Point Ruang Lab TKJ 3"
                       class="w-full px-3.5 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3F51B5]/30 focus:border-[#3F51B5]">
            </div>

            <!-- Jurusan / Unit -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                    Unit / Jurusan Pemohon
                </label>
                @if(Auth::user()->isJurusan() && Auth::user()->jurusan_id)
                    <input type="hidden" name="jurusan_id" value="{{ Auth::user()->jurusan_id }}">
                    <div class="px-3.5 py-2.5 text-sm bg-gray-100 border border-gray-200 rounded-xl text-gray-700 font-medium">
                        {{ Auth::user()->jurusan->nama_jurusan }} (Otomatis sesuai akun Anda)
                    </div>
                @else
                    <select name="jurusan_id" class="w-full px-3.5 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3F51B5]/30 focus:border-[#3F51B5]">
                        <option value="">Pusat NOC / Seluruh Sekolah</option>
                        @foreach($jurusans as $j)
                        <option value="{{ $j->id }}" {{ old('jurusan_id') == $j->id ? 'selected' : '' }}>
                            {{ $j->nama_jurusan }}
                        </option>
                        @endforeach
                    </select>
                @endif
            </div>

            <!-- Prioritas -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                    Tingkat Prioritas <span class="text-red-500">*</span>
                </label>
                <select name="priority" required class="w-full px-3.5 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3F51B5]/30 focus:border-[#3F51B5]">
                    <option value="sedang" {{ old('priority', 'sedang') === 'sedang' ? 'selected' : '' }}>🔹 Sedang (Kebutuhan Standar)</option>
                    <option value="tinggi" {{ old('priority') === 'tinggi' ? 'selected' : '' }}>⚡ Tinggi (Diperlukan dalam waktu dekat)</option>
                    <option value="mendesak" {{ old('priority') === 'mendesak' ? 'selected' : '' }}>🚨 Mendesak (Alat rusak kritis / Uji Kompetensi)</option>
                    <option value="rendah" {{ old('priority') === 'rendah' ? 'selected' : '' }}>⚪ Rendah (Rencana jangka panjang)</option>
                </select>
            </div>

            <!-- Target Tanggal Dibutuhkan -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                    Target Tanggal Kebutuhan
                </label>
                <input type="date" name="target_date" value="{{ old('target_date') }}"
                       class="w-full px-3.5 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3F51B5]/30 focus:border-[#3F51B5]">
            </div>

            <!-- Lampiran Proposal / Dokumen -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                    File Lampiran / Dokumen Penawaran (Opsional)
                </label>
                <input type="file" name="attachment" accept=".pdf,.png,.jpg,.jpeg,.doc,.docx,.xlsx"
                       class="w-full px-3 py-2 text-xs bg-gray-50 border border-gray-200 rounded-xl file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#3F51B5] file:text-white hover:file:bg-[#3949AB] cursor-pointer">
                <p class="text-[11px] text-gray-400 mt-1">Format: PDF, JPG, PNG, DOCX, XLSX (Maks. 5 MB)</p>
            </div>

            <!-- Latar Belakang / Urgensi -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                    Justifikasi / Latar Belakang Kebutuhan
                </label>
                <textarea name="justification" rows="3" placeholder="Jelaskan tujuan dan urgensi pengadaan barang ini, misal untuk praktikum siswa materi routing dinamis..."
                          class="w-full px-3.5 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3F51B5]/30 focus:border-[#3F51B5]">{{ old('justification') }}</textarea>
            </div>
        </div>
    </div>

    <!-- Section 2: Rincian Barang yang Diajukan -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-3 border-b border-gray-100 gap-2">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#3F51B5] text-[22px]">inventory</span>
                <h3 class="text-base font-bold text-gray-800">2. Rincian Barang / Alat yang Diajukan</h3>
            </div>
            <button type="button" onclick="addNewItemRow()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-[#3F51B5] hover:bg-blue-100 text-xs font-bold rounded-xl transition-colors self-start sm:self-auto">
                <span class="material-symbols-outlined text-[16px]">add</span>
                <span>Tambah Baris Barang</span>
            </button>
        </div>

        <!-- Container Baris Barang -->
        <div id="itemsContainer" class="space-y-3">
            <!-- Row Template akan di-render di sini via JS -->
        </div>

        <!-- Grand Total Summary Card -->
        <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-gray-200/80 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-[#3F51B5]/10 text-[#3F51B5] flex items-center justify-center font-bold">
                    <span class="material-symbols-outlined text-[20px]">calculate</span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 font-medium">Total Item Diajukan:</span>
                    <h5 id="totalItemsCountText" class="text-sm font-bold text-gray-800">1 Barang</h5>
                </div>
            </div>
            <div class="text-right">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Perkiraan Total Anggaran</span>
                <h3 id="grandTotalText" class="text-2xl font-black text-[#3F51B5] font-mono">Rp 0</h3>
            </div>
        </div>
    </div>

    <!-- Form Action Buttons -->
    <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-2">
        <a href="{{ route('procurements.index') }}" class="w-full sm:w-auto px-5 py-2.5 text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors text-center">
            Batal
        </a>
        <button type="button" onclick="submitForm('draft')" class="w-full sm:w-auto px-5 py-2.5 text-xs font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-[18px]">save</span>
            <span>Simpan sebagai Draf</span>
        </button>
        <button type="button" onclick="submitForm('submit')" class="w-full sm:w-auto px-6 py-2.5 text-xs font-bold text-white bg-[#3F51B5] hover:bg-[#3949AB] rounded-xl transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-[18px]">send</span>
            <span>Kirim Pengajuan Sekarang</span>
        </button>
    </div>
</form>

<!-- Javascript untuk Dynamic Rows & Kalkulasi Otomatis -->
<script>
    let rowIndex = 0;
    const categories = @json($categories);

    function addNewItemRow(data = null) {
        const container = document.getElementById('itemsContainer');
        const index = rowIndex++;

        let categoryOptions = `<option value="">-- Pilih Kategori --</option>`;
        categories.forEach(c => {
            const isSelected = data && data.category_id == c.id ? 'selected' : '';
            categoryOptions += `<option value="${c.id}" ${isSelected}>${c.name}</option>`;
        });

        const rowHtml = `
            <div class="item-row p-4 rounded-xl border border-gray-200 bg-white hover:border-[#3F51B5]/50 transition-all relative space-y-3" id="row-${index}">
                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <span class="text-xs font-bold text-gray-500 flex items-center gap-1.5">
                        <span class="w-5 h-5 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center text-[10px] row-number">1</span>
                        Barang #<span class="row-seq">${index + 1}</span>
                    </span>
                    <button type="button" onclick="removeRow(${index})" class="text-gray-400 hover:text-red-500 p-1 rounded-lg transition-colors" title="Hapus Baris">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-start">
                    <!-- Nama Barang -->
                    <div class="md:col-span-4">
                        <label class="block text-[11px] font-bold text-gray-600 mb-1">Nama Alat / Barang <span class="text-red-500">*</span></label>
                        <input type="text" name="items[${index}][item_name]" value="${data ? data.item_name : ''}" required
                               placeholder="Contoh: Router Mikrotik RB4011iGS+" 
                               class="w-full px-3 py-2 text-xs bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#3F51B5] focus:border-[#3F51B5]">
                    </div>

                    <!-- Kategori -->
                    <div class="md:col-span-3">
                        <label class="block text-[11px] font-bold text-gray-600 mb-1">Kategori</label>
                        <select name="items[${index}][category_id]" class="w-full px-3 py-2 text-xs bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#3F51B5]">
                            ${categoryOptions}
                        </select>
                    </div>

                    <!-- Qty & Satuan -->
                    <div class="md:col-span-2 grid grid-cols-2 gap-1.5">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 mb-1">Jumlah</label>
                            <input type="number" name="items[${index}][quantity]" value="${data ? data.quantity : 1}" min="1" required
                                   oninput="calculateSubtotal(${index})"
                                   id="qty-${index}"
                                   class="w-full px-2.5 py-2 text-xs bg-gray-50 border border-gray-200 rounded-lg text-center font-bold focus:outline-none focus:ring-1 focus:ring-[#3F51B5]">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 mb-1">Satuan</label>
                            <input type="text" name="items[${index}][unit]" value="${data ? data.unit : 'Unit'}" required
                                   placeholder="Pcs/Unit" 
                                   class="w-full px-2 py-2 text-xs bg-gray-50 border border-gray-200 rounded-lg text-center focus:outline-none focus:ring-1 focus:ring-[#3F51B5]">
                        </div>
                    </div>

                    <!-- Harga Satuan Estimasi -->
                    <div class="md:col-span-3">
                        <label class="block text-[11px] font-bold text-gray-600 mb-1">Est. Harga Satuan (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="items[${index}][estimated_unit_price]" value="${data ? data.estimated_unit_price : 0}" min="0" step="1000" required
                               oninput="calculateSubtotal(${index})"
                               id="price-${index}"
                               class="w-full px-3 py-2 text-xs bg-gray-50 border border-gray-200 rounded-lg font-mono font-semibold focus:outline-none focus:ring-1 focus:ring-[#3F51B5]">
                    </div>
                </div>

                <!-- Baris Tambahan: Spesifikasi, Link Referensi & Subtotal Display -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center pt-2 border-t border-gray-50">
                    <div class="md:col-span-5">
                        <input type="text" name="items[${index}][specification]" value="${data ? data.specification || '' : ''}"
                               placeholder="Spesifikasi / Merk / Tipe (opsional)" 
                               class="w-full px-3 py-1.5 text-xs bg-gray-50/70 border border-gray-200 rounded-lg text-gray-600 focus:outline-none">
                    </div>
                    <div class="md:col-span-4">
                        <input type="url" name="items[${index}][reference_url]" value="${data ? data.reference_url || '' : ''}"
                               placeholder="Link Referensi / E-Katalog (https://...)" 
                               class="w-full px-3 py-1.5 text-xs bg-gray-50/70 border border-gray-200 rounded-lg text-gray-600 focus:outline-none">
                    </div>
                    <div class="md:col-span-3 text-right">
                        <span class="text-[10px] text-gray-400 uppercase font-semibold">Subtotal:</span>
                        <span id="subtotal-${index}" class="text-xs font-bold font-mono text-gray-800 ml-1">Rp 0</span>
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', rowHtml);
        updateRowNumbers();
        calculateSubtotal(index);
    }

    function removeRow(index) {
        const rows = document.querySelectorAll('.item-row');
        if (rows.length <= 1) {
            alert('Minimal harus ada 1 baris barang dalam pengajuan.');
            return;
        }
        const targetRow = document.getElementById(`row-${index}`);
        if (targetRow) {
            targetRow.remove();
            updateRowNumbers();
            recalculateGrandTotal();
        }
    }

    function updateRowNumbers() {
        const rows = document.querySelectorAll('.item-row');
        rows.forEach((row, idx) => {
            const numSpan = row.querySelector('.row-number');
            const seqSpan = row.querySelector('.row-seq');
            if (numSpan) numSpan.textContent = idx + 1;
            if (seqSpan) seqSpan.textContent = idx + 1;
        });
        document.getElementById('totalItemsCountText').textContent = `${rows.length} Barang`;
    }

    function calculateSubtotal(index) {
        const qtyInput = document.getElementById(`qty-${index}`);
        const priceInput = document.getElementById(`price-${index}`);
        const subtotalSpan = document.getElementById(`subtotal-${index}`);

        if (qtyInput && priceInput && subtotalSpan) {
            const qty = parseFloat(qtyInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const subtotal = qty * price;
            subtotalSpan.textContent = formatRupiah(subtotal);
        }
        recalculateGrandTotal();
    }

    function recalculateGrandTotal() {
        let grandTotal = 0;
        const rows = document.querySelectorAll('.item-row');

        rows.forEach(row => {
            const id = row.id.replace('row-', '');
            const qty = parseFloat(document.getElementById(`qty-${id}`)?.value) || 0;
            const price = parseFloat(document.getElementById(`price-${id}`)?.value) || 0;
            grandTotal += (qty * price);
        });

        document.getElementById('grandTotalText').textContent = formatRupiah(grandTotal);
    }

    function formatRupiah(angka) {
        return 'Rp ' + Number(angka).toLocaleString('id-ID');
    }

    function submitForm(type) {
        document.getElementById('actionTypeInput').value = type;
        const form = document.getElementById('procurementForm');
        if (type === 'submit' && !form.checkValidity()) {
            form.reportValidity();
            return;
        }
        form.submit();
    }

    // Inisialisasi baris pertama saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        @if(old('items'))
            const oldItems = @json(old('items'));
            Object.values(oldItems).forEach(item => addNewItemRow(item));
        @else
            addNewItemRow();
        @endif
    });
</script>
@endsection
