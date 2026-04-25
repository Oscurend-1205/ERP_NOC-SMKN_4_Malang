<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Scanner Peminjaman - ERP NOC</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } }
        }
    </script>
    <style>
        * { -webkit-tap-highlight-color: transparent; }
        body { font-family: 'Inter', sans-serif; overscroll-behavior: none; }
        #reader { width: 100% !important; }
        #reader video { border-radius: 12px !important; }
        .fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes pulse-ring { 0% { transform: scale(0.8); opacity: 1; } 100% { transform: scale(1.4); opacity: 0; } }
        .scan-ring::before { content: ''; position: absolute; inset: -8px; border: 3px solid rgba(37, 99, 235, 0.4); border-radius: 16px; animation: pulse-ring 2s infinite; }
    </style>
</head>
<body class="bg-gray-950 text-white min-h-screen">
    
    <!-- Top Bar -->
    <div class="sticky top-0 z-50 bg-gray-900/95 backdrop-blur-md border-b border-gray-800 px-4 py-3">
        <div class="flex items-center justify-between max-w-lg mx-auto">
            <div>
                <h1 class="text-sm font-bold text-white">📦 Scanner Peminjaman</h1>
                <p class="text-[10px] text-gray-400">ERP NOC &mdash; SMKN 4 Malang</p>
            </div>
            <div class="flex items-center gap-2 text-xs">
                <span class="material-symbols-outlined text-orange-400 text-[14px]">timer</span>
                <span id="sessionCountdown" class="font-mono font-bold text-orange-400">--:--</span>
            </div>
        </div>
    </div>

    <div class="max-w-lg mx-auto px-4 py-4 space-y-4">

        <!-- Step 1: Camera Scanner -->
        <div id="step1" class="fade-in">
            <div class="bg-gray-900 rounded-2xl overflow-hidden border border-gray-800">
                <!-- Scanner Header -->
                <div class="px-4 py-3 bg-blue-600 flex items-center gap-2">
                    <span class="bg-white/20 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">1</span>
                    <span class="text-sm font-bold">Scan QR Barang</span>
                </div>
                <!-- Camera Area -->
                <div class="p-3">
                    <div id="reader" class="rounded-xl overflow-hidden relative scan-ring"></div>
                </div>
                <div class="px-4 pb-3 text-center">
                    <p class="text-gray-400 text-xs">Arahkan kamera ke stiker QR yang ada di barang.</p>
                </div>
            </div>
        </div>

        <!-- Step 2: Item Info (hidden initially) -->
        <div id="step2" class="hidden fade-in">
            <div class="bg-gray-900 rounded-2xl overflow-hidden border border-gray-800">
                <div class="px-4 py-3 bg-green-600 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="bg-white/20 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">2</span>
                        <span class="text-sm font-bold">Data Barang</span>
                    </div>
                    <button onclick="resetScanner()" class="text-xs bg-white/20 px-2 py-1 rounded-md hover:bg-white/30 transition-colors">
                        Scan Ulang
                    </button>
                </div>
                <div class="p-4 space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-blue-400 text-[24px]">devices</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p id="itemName" class="font-bold text-white text-base truncate"></p>
                            <p id="itemCode" class="text-xs text-gray-400 font-mono mt-0.5"></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="bg-gray-800/50 rounded-lg px-3 py-2">
                            <span class="text-gray-500">Kategori</span>
                            <p id="itemCategory" class="text-gray-300 font-medium mt-0.5"></p>
                        </div>
                        <div class="bg-gray-800/50 rounded-lg px-3 py-2">
                            <span class="text-gray-500">Lokasi</span>
                            <p id="itemLocation" class="text-gray-300 font-medium mt-0.5"></p>
                        </div>
                        <div class="bg-gray-800/50 rounded-lg px-3 py-2">
                            <span class="text-gray-500">Kondisi</span>
                            <p id="itemCondition" class="text-gray-300 font-medium mt-0.5"></p>
                        </div>
                        <div class="bg-gray-800/50 rounded-lg px-3 py-2">
                            <span class="text-gray-500">Stok</span>
                            <p id="itemQty" class="text-gray-300 font-medium mt-0.5"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: Form Peminjaman (hidden initially) -->
        <div id="step3" class="hidden fade-in">
            <div class="bg-gray-900 rounded-2xl overflow-hidden border border-gray-800">
                <div class="px-4 py-3 bg-purple-600 flex items-center gap-2">
                    <span class="bg-white/20 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">3</span>
                    <span class="text-sm font-bold">Form Peminjaman</span>
                </div>
                <div class="p-4 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 mb-1.5">Nama Lengkap</label>
                        <input type="text" id="inputNama" placeholder="Masukkan nama lengkap" 
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 mb-1.5">Kelas</label>
                        <input type="text" id="inputKelas" placeholder="Contoh: XII TKJ 2" 
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 mb-1.5">Catatan (opsional)</label>
                        <textarea id="inputCatatan" rows="2" placeholder="Keperluan pinjaman..." 
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                    </div>
                    <div class="bg-gray-800/50 rounded-lg px-3 py-2 text-xs flex items-center justify-between">
                        <span class="text-gray-500">Waktu Pinjam</span>
                        <span id="currentTime" class="text-blue-400 font-mono font-bold"></span>
                    </div>
                    <button id="btnSubmit" onclick="submitPeminjaman()" 
                        class="w-full py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 active:scale-[0.98] transition-all text-sm flex items-center justify-center gap-2 shadow-lg shadow-blue-500/25">
                        <span class="material-symbols-outlined text-[18px]">send</span>
                        Kirim Peminjaman
                    </button>
                </div>
            </div>
        </div>

        <!-- Success Message (hidden) -->
        <div id="successMessage" class="hidden fade-in">
            <div class="bg-green-900/50 border border-green-700 rounded-2xl p-6 text-center">
                <div class="w-16 h-16 mx-auto mb-3 bg-green-500 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-[32px]">check_circle</span>
                </div>
                <h3 class="text-lg font-bold text-green-400">Peminjaman Berhasil!</h3>
                <p id="successDetail" class="text-green-300/70 text-sm mt-1"></p>
                <button onclick="resetAll()" class="mt-4 px-6 py-2 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition-colors text-sm">
                    Pinjam Barang Lain
                </button>
            </div>
        </div>

        <!-- Error Message (hidden) -->
        <div id="errorMessage" class="hidden fade-in">
            <div class="bg-red-900/50 border border-red-700 rounded-2xl p-4 text-center">
                <span class="material-symbols-outlined text-red-400 text-[28px]">error</span>
                <p id="errorText" class="text-red-300 text-sm mt-1 font-medium"></p>
                <button onclick="hideError()" class="mt-2 text-xs text-red-400 underline">Tutup</button>
            </div>
        </div>
    </div>

    <!-- html5-qrcode Library -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
    const TOKEN = '{{ $token }}';
    const EXPIRY = new Date('{{ $expired_at }}');
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    let html5QrCode = null;
    let currentItem = null;

    // ============================================================
    // SESSION COUNTDOWN
    // ============================================================
    function updateSessionCountdown() {
        const now = new Date();
        const diff = EXPIRY - now;
        const el = document.getElementById('sessionCountdown');

        if (diff <= 0) {
            el.textContent = 'EXPIRED';
            el.classList.add('text-red-400');
            el.classList.remove('text-orange-400');
            // Disable everything
            document.getElementById('btnSubmit')?.setAttribute('disabled', true);
            return;
        }

        const mins = Math.floor(diff / 60000);
        const secs = Math.floor((diff % 60000) / 1000);
        el.textContent = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    }
    setInterval(updateSessionCountdown, 1000);
    updateSessionCountdown();

    // ============================================================
    // QR SCANNER (Camera)
    // ============================================================
    function startScanner() {
        html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 220, height: 220 }, aspectRatio: 1.0 },
            onScanSuccess,
            () => {} // ignore errors
        ).catch(err => {
            console.error("Camera error:", err);
            showError("Gagal mengakses kamera. Pastikan izin kamera diaktifkan.");
        });
    }

    async function onScanSuccess(decodedText) {
        // Stop scanner temporarily
        if (html5QrCode) {
            await html5QrCode.stop();
        }

        // Play beep
        playBeep();

        // Lookup item
        await lookupItem(decodedText);
    }

    async function lookupItem(code) {
        try {
            const response = await fetch(`/scan/${TOKEN}/lookup/${encodeURIComponent(code)}`);
            const data = await response.json();

            if (data.success) {
                currentItem = data.item;
                showItemInfo(data.item);
            } else {
                showError(data.message);
                // Restart scanner after error
                setTimeout(() => startScanner(), 2000);
            }
        } catch (e) {
            showError('Gagal mengambil data barang. Coba lagi.');
            setTimeout(() => startScanner(), 2000);
        }
    }

    function showItemInfo(item) {
        document.getElementById('itemName').textContent = item.name;
        document.getElementById('itemCode').textContent = `Kode: ${item.code}`;
        document.getElementById('itemCategory').textContent = item.category;
        document.getElementById('itemLocation').textContent = item.location;
        document.getElementById('itemCondition').textContent = item.condition;
        document.getElementById('itemQty').textContent = `${item.quantity} unit`;

        document.getElementById('step1').classList.add('hidden');
        document.getElementById('step2').classList.remove('hidden');
        document.getElementById('step3').classList.remove('hidden');

        // Update time display
        updateCurrentTime();
        setInterval(updateCurrentTime, 1000);
    }

    // ============================================================
    // FORM SUBMISSION
    // ============================================================
    async function submitPeminjaman() {
        const nama = document.getElementById('inputNama').value.trim();
        const kelas = document.getElementById('inputKelas').value.trim();
        const catatan = document.getElementById('inputCatatan').value.trim();

        if (!nama) { showError('Nama wajib diisi!'); return; }
        if (!kelas) { showError('Kelas wajib diisi!'); return; }
        if (!currentItem) { showError('Barang belum di-scan!'); return; }

        const btn = document.getElementById('btnSubmit');
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined text-[18px] animate-spin">progress_activity</span> Mengirim...';

        try {
            const response = await fetch(`/scan/${TOKEN}/submit`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                },
                body: JSON.stringify({
                    nama_peminjam: nama,
                    kelas: kelas,
                    item_id: currentItem.id,
                    item_code: currentItem.code,
                    catatan: catatan || null,
                }),
            });

            const data = await response.json();

            if (data.success) {
                playSuccessBeep();
                showSuccess(data.data);
            } else {
                showError(data.message);
            }
        } catch (e) {
            showError('Gagal mengirim data. Periksa koneksi internet.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">send</span> Kirim Peminjaman';
        }
    }

    function showSuccess(data) {
        document.getElementById('step2').classList.add('hidden');
        document.getElementById('step3').classList.add('hidden');
        document.getElementById('successMessage').classList.remove('hidden');
        document.getElementById('successDetail').textContent = 
            `${data.nama} (${data.kelas}) meminjam "${data.barang}" pada ${data.waktu}`;
    }

    // ============================================================
    // HELPERS
    // ============================================================
    function resetScanner() {
        currentItem = null;
        document.getElementById('step1').classList.remove('hidden');
        document.getElementById('step2').classList.add('hidden');
        document.getElementById('step3').classList.add('hidden');
        startScanner();
    }

    function resetAll() {
        currentItem = null;
        document.getElementById('inputNama').value = '';
        document.getElementById('inputKelas').value = '';
        document.getElementById('inputCatatan').value = '';
        document.getElementById('successMessage').classList.add('hidden');
        document.getElementById('step1').classList.remove('hidden');
        document.getElementById('step2').classList.add('hidden');
        document.getElementById('step3').classList.add('hidden');
        startScanner();
    }

    function showError(msg) {
        document.getElementById('errorText').textContent = msg;
        document.getElementById('errorMessage').classList.remove('hidden');
        setTimeout(hideError, 4000);
    }

    function hideError() {
        document.getElementById('errorMessage').classList.add('hidden');
    }

    function updateCurrentTime() {
        const now = new Date();
        document.getElementById('currentTime').textContent = 
            now.toLocaleDateString('id-ID') + ' ' + now.toLocaleTimeString('id-ID');
    }

    function playBeep() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain); gain.connect(ctx.destination);
            osc.frequency.value = 1200; osc.type = 'sine'; gain.gain.value = 0.3;
            osc.start(); setTimeout(() => { osc.stop(); ctx.close(); }, 150);
        } catch(e) {}
    }

    function playSuccessBeep() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            [800, 1000, 1200].forEach((freq, i) => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain); gain.connect(ctx.destination);
                osc.frequency.value = freq; osc.type = 'sine'; gain.gain.value = 0.2;
                osc.start(ctx.currentTime + i * 0.12);
                osc.stop(ctx.currentTime + i * 0.12 + 0.1);
            });
            setTimeout(() => ctx.close(), 500);
        } catch(e) {}
    }

    // ============================================================
    // INIT
    // ============================================================
    document.addEventListener('DOMContentLoaded', () => {
        startScanner();
    });
    </script>
</body>
</html>
