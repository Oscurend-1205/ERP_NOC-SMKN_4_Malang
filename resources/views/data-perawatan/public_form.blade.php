<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Laporan Perbaikan - {{ $perawatan->item->code ?? 'Aset' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#3F51B5] to-[#5C6BC0] px-6 py-8 text-center text-white relative">
            <span class="material-symbols-outlined text-[48px] opacity-90 mb-2">build_circle</span>
            <h1 class="text-2xl font-bold">Laporan Teknisi</h1>
            <p class="text-sm opacity-80 mt-1">Sistem ERP NOC SMKN 4 Malang</p>
        </div>

        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 text-center font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Read-only Context -->
            <div class="bg-amber-50 border border-amber-100 rounded-xl p-5 mb-6">
                <h3 class="text-xs font-bold text-amber-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">info</span> Detail Barang
                </h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-[10px] text-amber-700/70 font-bold uppercase">Nama Barang</p>
                        <p class="font-semibold text-amber-900">{{ $perawatan->item->name ?? '-' }} ({{ $perawatan->item->code ?? '-' }})</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-amber-700/70 font-bold uppercase">Kendala / Keluhan Awal</p>
                        <p class="text-sm text-amber-900 font-medium">{{ $perawatan->catatan ?: 'Tidak ada catatan.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Laporan -->
            <form action="{{ route('maintenance.public_submit', $token) }}" method="POST" enctype="multipart/form-data" class="space-y-5" onsubmit="document.getElementById('btnSubmit').innerHTML='Mengunggah...'; document.getElementById('btnSubmit').disabled=true;">
                @csrf
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-gray-700">Nama Teknisi / Vendor <span class="text-red-500">*</span></label>
                    <input type="text" name="teknisi_nama" required placeholder="Masukkan nama Anda / nama toko" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#3F51B5] outline-none transition-all bg-gray-50">
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-gray-700">Total Biaya Perbaikan (Rp)</label>
                    <input type="number" name="biaya" placeholder="Contoh: 150000" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#3F51B5] outline-none transition-all bg-gray-50">
                    <p class="text-[11px] text-gray-500 mt-1">Kosongkan jika tidak ada biaya.</p>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-gray-700">Foto Bukti / Nota <span class="text-red-500">*</span></label>
                    <input type="file" name="foto_bukti" required accept="image/*" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-[#3F51B5] hover:file:bg-indigo-100 cursor-pointer">
                    <p class="text-[11px] text-gray-500 mt-1">Upload foto barang setelah diperbaiki atau nota servis (Maks 5MB).</p>
                </div>

                <button type="submit" id="btnSubmit" class="w-full py-3.5 bg-[#4F46E5] text-white font-bold rounded-xl hover:bg-[#4338CA] transition-all shadow-md active:scale-95 flex items-center justify-center gap-2 mt-6">
                    <span class="material-symbols-outlined">send</span> Kirim Laporan
                </button>
            </form>
        </div>
    </div>
    
    <p class="mt-6 text-xs text-gray-400 font-medium text-center">&copy; {{ date('Y') }} ERP NOC SMKN 4 Malang.</p>
</body>
</html>
