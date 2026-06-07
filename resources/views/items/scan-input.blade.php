<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Scan & Tambah Barang - ERP NOC</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }
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
        #reader video { object-fit: cover !important; transform: scaleX(1) !important; -webkit-transform: scaleX(1) !important; }
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

        <!-- BEGIN: Page Content -->
        <div id="pjax-content" class="p-4 md:p-10 pt-4 md:pt-6 space-y-6">
            
            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Scan & Tambah Barang</h2>
                    <p class="text-sm text-gray-500 mt-1">Gunakan kamera untuk scan stiker QR lalu lengkapi data barang</p>
                </div>
                <a href="{{ route('items.index') }}" class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-all text-sm border border-gray-200">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Kembali
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">
                {{-- Scanner Section (Left) --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden lg:col-span-2">
                    <div class="px-6 py-4 bg-gray-900 text-white flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-400">photo_camera</span>
                            <h2 class="font-bold text-sm">Camera Scanner</h2>
                        </div>
                        <div id="scannerStatus" class="flex items-center gap-1.5 text-[10px] font-bold text-green-400">
                            <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span> AKTIF
                        </div>
                    </div>
                    <div class="relative bg-black h-[350px] w-full">
                        <div id="reader" class="w-full h-full overflow-hidden"></div>
                        <div class="absolute inset-0 border-[40px] border-black/50 pointer-events-none flex items-center justify-center">
                            <div class="w-[180px] h-[180px] border-2 border-white/40 rounded-xl relative">
                                <div class="absolute -top-0.5 -left-0.5 w-5 h-5 border-t-4 border-l-4 border-blue-500 rounded-tl-lg"></div>
                                <div class="absolute -top-0.5 -right-0.5 w-5 h-5 border-t-4 border-r-4 border-blue-500 rounded-tr-lg"></div>
                                <div class="absolute -bottom-0.5 -left-0.5 w-5 h-5 border-b-4 border-l-4 border-blue-500 rounded-bl-lg"></div>
                                <div class="absolute -bottom-0.5 -right-0.5 w-5 h-5 border-b-4 border-r-4 border-blue-500 rounded-br-lg"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Section (Right) --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden lg:col-span-3">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#3F51B5]">edit_document</span>
                        <h2 class="font-bold text-gray-800">Form Input Barang</h2>
                    </div>
                    <div class="p-4 md:p-5">
                        <form id="addItemForm" onsubmit="submitItem(event)" class="flex flex-col gap-3.5">
                            @csrf
                            
                            <div id="scanAlert" class="hidden bg-blue-50 border border-blue-100 text-blue-800 px-3 py-2 rounded-lg text-xs font-semibold flex items-center gap-2">
                                <span class="material-symbols-outlined text-blue-600 text-[16px]">check_circle</span>
                                QR Code Terdeteksi! Silakan lengkapi data.
                            </div>

                            <div class="space-y-1">
                                <label class="block text-[10px] font-bold text-gray-500 tracking-wide">KODE BARANG (DARI SCAN)</label>
                                <input type="text" id="code" name="code" readonly required placeholder="Scan stiker QR..." 
                                    class="w-full px-3 py-1.5 text-sm bg-gray-50 border border-gray-200 rounded-lg font-mono font-bold text-[#3F51B5] focus:outline-none">
                            </div>

                            <div class="space-y-1">
                                <label class="block text-[10px] font-bold text-gray-500 tracking-wide">NAMA BARANG</label>
                                <input type="text" id="name" name="name" required disabled placeholder="Contoh: Monitor LG 24'"
                                    class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-[#3F51B5] focus:border-[#3F51B5] outline-none transition-all disabled:bg-gray-100 disabled:text-gray-400">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-bold text-gray-500 tracking-wide">KATEGORI</label>
                                    <select id="category_id" name="category_id" required disabled class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-[#3F51B5] outline-none transition-all disabled:bg-gray-100 disabled:text-gray-400">
                                        <option value="">-- Pilih --</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-bold text-gray-500 tracking-wide">KONDISI</label>
                                    <select id="condition" name="condition" required disabled class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-[#3F51B5] outline-none transition-all disabled:bg-gray-100 disabled:text-gray-400">
                                        <option value="baik">Baik</option>
                                        <option value="rusak_ringan">Rusak Ringan</option>
                                        <option value="rusak_berat">Rusak Berat</option>
                                        <option value="hilang">Hilang</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-bold text-gray-500 tracking-wide">TANGGAL MASUK</label>
                                    <input type="date" id="purchase_date" name="purchase_date" required disabled value="{{ date('Y-m-d') }}"
                                        class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-[#3F51B5] outline-none transition-all disabled:bg-gray-100 disabled:text-gray-400">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-bold text-gray-500 tracking-wide">LOKASI LAB</label>
                                    <select id="location_id" name="location_id" required disabled class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-[#3F51B5] outline-none transition-all disabled:bg-gray-100 disabled:text-gray-400">
                                        @foreach($locations as $loc)
                                            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100 mt-4">
                                <button type="button" onclick="resetForm()" class="px-4 py-1.5 text-[12px] font-bold text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Reset</button>
                                <button type="submit" id="btnSubmit" disabled class="flex items-center gap-1.5 px-4 py-1.5 text-[12px] font-bold text-white bg-[#3F51B5] rounded-lg hover:bg-[#3949AB] transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="material-symbols-outlined text-[16px]">save</span> Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    let html5QrCode;
    function startCamera() {
        html5QrCode = new Html5Qrcode("reader");
        
        // Coba kamera belakang dulu (environment), jika gagal coba kamera apa saja (user/laptop)
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 250, height: 250 }, disableFlip: true },
            onScanSuccess
        ).catch(err => {
            console.warn("Kamera belakang tidak ditemukan, mencoba kamera depan/laptop...", err);
            // Fallback ke kamera default (biasanya satu-satunya kamera di laptop)
            html5QrCode.start(
                { facingMode: "user" },
                { fps: 10, qrbox: { width: 250, height: 250 }, disableFlip: true },
                onScanSuccess
            ).catch(err2 => {
                console.error("Gagal memulai kamera:", err2);
                document.getElementById('scannerStatus').innerHTML = '<span class="text-red-400">Kamera Error/Izin Ditolak</span>';
                Swal.fire({
                    icon: 'error',
                    title: 'Kamera Tidak Akses',
                    text: 'Pastikan Anda memberikan izin kamera dan tidak ada aplikasi lain yang sedang menggunakan kamera.',
                    confirmButtonColor: '#3F51B5'
                });
            });
        });
    }

    function onScanSuccess(decodedText) {
        document.getElementById('code').value = decodedText;
        
        const alertBox = document.getElementById('scanAlert');
        alertBox.classList.remove('hidden');
        alertBox.classList.add('flex');
        
        document.querySelectorAll('#addItemForm input:not([readonly]), #addItemForm select, #addItemForm button').forEach(el => el.disabled = false);
        playBeep();
        html5QrCode.pause();
        document.getElementById('name').focus();
    }

    document.addEventListener('DOMContentLoaded', () => {
        startCamera();
    });

    function resetForm() {
        document.getElementById('addItemForm').reset();
        
        const alertBox = document.getElementById('scanAlert');
        alertBox.classList.add('hidden');
        alertBox.classList.remove('flex');
        
        document.querySelectorAll('#addItemForm input:not([readonly]), #addItemForm select, #addItemForm button').forEach(el => el.disabled = true);
        html5QrCode.resume();
    }

    async function submitItem(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSubmit');
        btn.disabled = true;
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());

        try {
            const res = await fetch('{{ route("items.store-scan") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(data)
            });
            const result = await res.json();
            if (res.ok) {
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Data tersimpan.', timer: 1500, showConfirmButton: false });
                resetForm();
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: result.message || 'Kode QR sudah ada!', confirmButtonColor: '#3F51B5' });
            }
        } catch (err) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menghubungi server.', confirmButtonColor: '#3F51B5' });
        } finally {
            btn.disabled = false;
        }
    }

    function playBeep() {
        try {
            const c = new (window.AudioContext || window.webkitAudioContext)();
            const o = c.createOscillator(); const g = c.createGain();
            o.connect(g); g.connect(c.destination); o.frequency.value = 800; o.type = 'sine'; g.gain.value = 0.3;
            o.start(); setTimeout(() => { o.stop(); c.close(); }, 200);
        } catch(e) {}
    }
    </script>
    @vite(['resources/js/turbo-navigation.js'])
    @include('components.accessibility-button')
</body>
</html>
