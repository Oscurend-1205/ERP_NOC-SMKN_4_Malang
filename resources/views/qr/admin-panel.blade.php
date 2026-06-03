<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>QR Lending Panel - ERP NOC</title>
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
    <main class="flex-grow flex flex-col h-screen overflow-y-auto transition-all duration-300 w-full min-w-0">
        @include('partials.topbar')

        <!-- BEGIN: Page Content -->
        <div class="p-4 md:p-10 pt-4 md:pt-6 space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">QR Peminjaman</h2>
                    <p class="text-sm text-gray-500 mt-1">Kelola sesi peminjaman barang real-time via QR Code</p>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Input Pinjaman Button -->
                    <button onclick="document.getElementById('loanModal').classList.remove('hidden')" class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 font-semibold rounded-lg hover:bg-blue-100 transition-all shadow-sm active:scale-95 text-sm border border-blue-200">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        Input Pinjaman
                    </button>
                </div>
            </div>

            @if($activeSessions->count() > 0)
                @php 
                    $s = $activeSessions->first(); 
                    $scanUrl = route('qr.scan', ['token' => $s->token]);
                @endphp
                <div id="activeSessionData" 
                     data-token="{{ $s->token }}" 
                     data-url="{{ $scanUrl }}" 
                     data-time="{{ $s->expired_at->format('H:i') }}" 
                     data-full="{{ $s->expired_at->toIso8601String() }}" 
                     class="hidden"></div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                
                {{-- LEFT COLUMN: QR & Settings --}}
                <div class="space-y-6 lg:col-span-1">
                    {{-- QR Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden border-t-4 border-t-[#3F51B5]">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#3F51B5]">qr_code_scanner</span>
                            <h2 class="text-sm font-bold text-gray-800">QR Session Aktif</h2>
                        </div>
                        <div class="p-8 text-center flex flex-col items-center justify-center">
                            
                            <div id="qrPlaceholder" class="py-10 text-gray-400 text-sm flex flex-col items-center">
                                <span class="material-symbols-outlined text-[64px] mb-4 opacity-30">qr_code</span>
                                <p>Belum ada QR aktif.<br>Klik tombol di bawah untuk memulai.</p>
                            </div>
                            
                            <div id="qrActive" class="hidden w-full">
                                <div id="qrImageWrapper" onclick="openQrModal()" class="bg-white p-4 rounded-xl inline-block shadow-sm border border-gray-100 mb-4 cursor-pointer hover:scale-[1.03] transition-transform duration-200" title="Klik untuk memperbesar"></div>
                                <div class="text-sm font-bold text-[#3F51B5]">Scan QR untuk Memulai</div>
                                
                                <div class="mt-4 p-4 bg-gray-50 rounded-xl">
                                    <div class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Waktu Tersisa</div>
                                    <div id="qrCountdown" class="text-3xl font-extrabold text-[#3F51B5] font-mono tracking-widest">00:00</div>
                                </div>

                                <div class="text-xs text-gray-500 mt-4">
                                    Berlaku sampai: <span id="qrExpiry" class="text-orange-500 font-bold"></span>
                                </div>
                                
                                <button onclick="revokeCurrentToken()" class="mt-4 text-xs font-semibold text-red-500 hover:text-red-700 underline transition-colors">
                                    Batalkan Sesi Sekarang
                                </button>
                            </div>

                            <!-- Generate QR Session Button -->
                            <button id="btnGenerateQr" onclick="generateQR()" class="mt-6 flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-[#3F51B5] text-white font-semibold rounded-lg hover:bg-[#3949AB] transition-all shadow-sm active:scale-95 text-sm">
                                <span class="material-symbols-outlined text-[18px]">qr_code</span>
                                Generate QR Session
                            </button>
                        </div>
                    </div>

                    {{-- Settings Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                            <span class="material-symbols-outlined text-gray-500 text-[20px]">settings</span>
                            <h2 class="text-sm font-bold text-gray-800">Pengaturan Sesi</h2>
                        </div>
                        <div class="p-6">
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Durasi Berlaku</label>
                            <select id="expiryMinutes" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] outline-none transition-all cursor-pointer bg-gray-50">
                                <option value="5">5 Menit (Cepat)</option>
                                <option value="10" selected>10 Menit (Standar)</option>
                                <option value="15">15 Menit</option>
                                <option value="30">30 Menit</option>
                                <option value="60">1 Jam</option>
                            </select>
                            <p class="text-[11px] text-gray-400 mt-3 leading-relaxed">
                                Sesi akan otomatis berakhir setelah waktu habis untuk keamanan peminjaman.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: Live Feed List --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden lg:col-span-2 flex flex-col min-h-[500px]">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#3F51B5] text-[20px]">sensors</span>
                            <h2 class="text-sm font-bold text-gray-800">Peminjaman Masuk (Live Feed)</h2>
                        </div>
                        <div id="liveIndicator" class="flex items-center gap-2 text-[11px] font-bold text-green-500">
                            <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse ring-4 ring-green-500/20"></span>
                            TERHUBUNG
                        </div>
                    </div>
                    <div class="p-0 overflow-x-auto flex-grow max-h-[600px] overflow-y-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="sticky top-0 bg-white z-10 shadow-[0_1px_0_#F3F4F6]">
                                <tr>
                                    <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Peminjam</th>
                                    <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Kelas</th>
                                    <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Barang</th>
                                    <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu</th>
                                    <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody id="peminjamanTableBody" class="divide-y divide-gray-100">
                                @forelse($recentPeminjaman as $p)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-6 font-semibold text-sm text-gray-800">{{ $p->nama_peminjam }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $p->kelas }}</td>
                                    <td class="py-4 px-6">
                                        <div class="font-medium text-sm text-gray-800">{{ $p->item->name ?? '-' }}</div>
                                        <code class="text-[10px] text-[#3F51B5] bg-indigo-50 px-2 py-0.5 rounded mt-0.5 inline-block">{{ $p->item_code }}</code>
                                    </td>
                                    <td class="py-4 px-6 text-xs text-gray-500 font-medium">{{ $p->waktu_pinjam->format('H:i:s') }}</td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold {{ $p->status === 'dipinjam' ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700' }}">
                                            {{ $p->status_label }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr id="emptyRow">
                                    <td colspan="5" class="py-24 text-center text-gray-400">
                                        <span class="material-symbols-outlined text-[64px] mb-4 opacity-20">inbox</span>
                                        <div class="font-semibold text-gray-600">Belum ada peminjaman</div>
                                        <div class="text-xs mt-1">Data akan muncul otomatis saat siswa melakukan scan.</div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Full Screen QR -->
        <div id="fullQrModal" class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 hidden" onclick="closeQrModal(event)">
            <div class="relative bg-white p-8 rounded-2xl max-w-md w-full mx-4 flex flex-col items-center justify-center shadow-2xl transition-all duration-300 transform scale-95" onclick="event.stopPropagation()">
                <!-- Close Button -->
                <button onclick="closeQrModal(event)" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer border-none bg-transparent">
                    <span class="material-symbols-outlined text-[28px]">close</span>
                </button>
                <!-- Modal QR Title -->
                <h3 class="text-lg font-bold text-gray-800 mb-6 text-center">Scan QR Code</h3>
                <!-- Modal QR Image Wrapper -->
                <div id="modalQrWrapper" class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center justify-center"></div>
                <div class="mt-6 text-center w-full">
                    <div class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Waktu Tersisa</div>
                    <div id="modalQrCountdown" class="text-4xl font-extrabold text-[#3F51B5] font-mono tracking-widest">00:00</div>
                </div>
            </div>
        </div>

        <!-- Modal Input Pinjaman -->
        <div id="loanModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('loanModal').classList.add('hidden')"></div>
            
            <!-- Modal Content -->
            <div class="relative w-full max-w-[600px] bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-white">
                    <h2 class="text-[18px] font-bold text-gray-800">Input Pinjaman</h2>
                    <button onclick="document.getElementById('loanModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-700 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <!-- Form -->
                <form action="{{ route('movements.loan') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
                    @csrf
                    
                    <div class="px-6 py-5 space-y-4 overflow-y-auto">
                        <!-- Nama Lengkap -->
                        <div class="space-y-1.5 text-left">
                            <label class="block text-[13px] font-bold text-gray-700">Nama Lengkap</label>
                            <input type="text" name="borrower_name" required placeholder="Masukkan nama lengkap" 
                                class="w-full px-3 py-2 text-[13px] bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] transition-all placeholder:text-gray-400 outline-none">
                        </div>

                        <!-- ID -->
                        <div class="space-y-1.5 text-left">
                            <label class="block text-[13px] font-bold text-gray-700">ID</label>
                            <input type="text" name="borrower_id" required placeholder="Masukkan ID peminjam" 
                                class="w-full px-3 py-2 text-[13px] bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] transition-all placeholder:text-gray-400 outline-none">
                        </div>

                        <!-- No HP -->
                        <div class="space-y-1.5 text-left">
                            <label class="block text-[13px] font-bold text-gray-700">No HP</label>
                            <input type="text" id="borrower_phone_input" name="borrower_phone" required placeholder="Masukan nomor HP *081..." 
                                class="w-full px-3 py-2 text-[13px] bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] transition-all placeholder:text-gray-400 outline-none">
                        </div>

                        <!-- Nama Barang -->
                        <div class="space-y-1.5 text-left">
                            <label class="block text-[13px] font-bold text-gray-700">Nama Barang</label>
                            <select name="item_id" required class="w-full px-3 py-2 text-[13px] bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] transition-all text-gray-700 outline-none cursor-pointer">
                                <option value="" disabled selected>Masukan nama barang</option>
                                @php /** @var \App\Models\Item $item */ @endphp
                                @foreach($availableItems ?? [] as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }} (Stok: {{ $item->quantity }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Jumlah -->
                        <div class="space-y-1.5 text-left">
                            <label class="block text-[13px] font-bold text-gray-700">Jumlah</label>
                            <input type="number" name="quantity" required min="1" placeholder="Masukkan jumlah" 
                                class="w-full px-3 py-2 text-[13px] bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] transition-all placeholder:text-gray-400 outline-none">
                        </div>

                        <!-- Tanggal Peminjaman -->
                        <div class="space-y-1.5 text-left">
                            <label class="block text-[13px] font-bold text-gray-700">Tanggal Peminjaman</label>
                            <input type="date" name="movement_date" required value="{{ date('Y-m-d') }}"
                                class="w-full px-3 py-2 text-[13px] bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] transition-all text-gray-700 outline-none">
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3 mt-auto">
                        <button type="button" onclick="document.getElementById('loanModal').classList.add('hidden')" 
                                class="px-5 py-2 text-[13px] font-bold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-5 py-2 text-[13px] font-bold text-white bg-[#3F51B5] rounded-lg hover:bg-[#3949AB] transition-colors shadow-sm">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
    let currentToken = null;
    let countdownInterval = null;
    let pollInterval = null;
    let lastPollTime = "{{ now()->toIso8601String() }}";

    async function generateQR() {
        const minutes = document.getElementById('expiryMinutes').value;
        const btn = document.getElementById('btnGenerateQr');
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined text-[18px] animate-spin">refresh</span> Generating...';

        try {
            const res = await fetch('{{ route("qr.generate") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ expiry_minutes: minutes }),
            });
            const data = await res.json();
            if (data.success) {
                currentToken = data.token;
                showQR(data.scan_url, data.expired_at, data.expired_at_full);
                startPolling();
            }
        } catch (e) {
            alert('Gagal generate QR. Coba lagi.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">qr_code</span> Generate QR Session';
        }
    }

    function showQR(url, time, full) {
        document.getElementById('qrPlaceholder').style.display = 'none';
        document.getElementById('qrActive').style.display = 'block';
        document.getElementById('qrExpiry').textContent = time;

        // Render small QR (color changed to black #000000)
        const wrapper = document.getElementById('qrImageWrapper');
        wrapper.innerHTML = '';
        const div = document.createElement('div');
        wrapper.appendChild(div);
        new QRCode(div, { text: url, width: 200, height: 200, colorDark: "#000000", colorLight: "#ffffff", correctLevel: QRCode.CorrectLevel.H });

        // Render large QR for modal (color changed to black #000000)
        const modalWrapper = document.getElementById('modalQrWrapper');
        modalWrapper.innerHTML = '';
        const modalDiv = document.createElement('div');
        modalWrapper.appendChild(modalDiv);
        new QRCode(modalDiv, { text: url, width: 320, height: 320, colorDark: "#000000", colorLight: "#ffffff", correctLevel: QRCode.CorrectLevel.H });

        startCountdown(new Date(full));
    }

    function startCountdown(exp) {
        if (countdownInterval) clearInterval(countdownInterval);
        countdownInterval = setInterval(() => {
            const diff = exp - new Date();
            if (diff <= 0) {
                clearInterval(countdownInterval);
                const statusText = 'EXPIRED';
                
                document.getElementById('qrCountdown').textContent = statusText;
                document.getElementById('qrCountdown').classList.add('text-red-500');
                document.getElementById('qrCountdown').classList.remove('text-[#3F51B5]');
                
                document.getElementById('modalQrCountdown').textContent = statusText;
                document.getElementById('modalQrCountdown').classList.add('text-red-500');
                document.getElementById('modalQrCountdown').classList.remove('text-[#3F51B5]');
                
                currentToken = null;
                return;
            }
            const m = Math.floor(diff / 60000), s = Math.floor((diff % 60000) / 1000);
            const formatTime = `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
            
            document.getElementById('qrCountdown').textContent = formatTime;
            document.getElementById('modalQrCountdown').textContent = formatTime;
        }, 1000);
    }

    function openQrModal() {
        const modal = document.getElementById('fullQrModal');
        if (modal) {
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.firstElementChild.classList.remove('scale-95');
                modal.firstElementChild.classList.add('scale-100');
            }, 10);
        }
    }

    function closeQrModal(event) {
        if (event) event.preventDefault();
        const modal = document.getElementById('fullQrModal');
        if (modal) {
            modal.firstElementChild.classList.remove('scale-100');
            modal.firstElementChild.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }
    }

    function startPolling() {
        if (pollInterval) clearInterval(pollInterval);
        pollInterval = setInterval(async () => {
            try {
                const res = await fetch(`{{ route("qr.poll") }}?since=${encodeURIComponent(lastPollTime)}`);
                const r = await res.json();
                if (r.success && r.data.length > 0) {
                    r.data.forEach(addRow);
                    lastPollTime = r.server_time;
                    playBeep();
                }
            } catch (e) {}
        }, 3000);
    }

    function addRow(item) {
        const tbody = document.getElementById('peminjamanTableBody');
        const empty = document.getElementById('emptyRow');
        if (empty) empty.remove();
        
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-gray-50 transition-colors bg-green-50 duration-1000';
        tr.innerHTML = `
            <td class="py-4 px-6 font-semibold text-sm text-gray-800">${item.nama_peminjam}</td>
            <td class="py-4 px-6 text-sm text-gray-600">${item.kelas}</td>
            <td class="py-4 px-6">
                <div class="font-medium text-sm text-gray-800">${item.item_name}</div>
                <code class="text-[10px] text-[#3F51B5] bg-indigo-50 px-2 py-0.5 rounded mt-0.5 inline-block">${item.item_code}</code>
            </td>
            <td class="py-4 px-6 text-xs text-gray-500 font-medium">${item.waktu_pinjam.split(' ')[1]}</td>
            <td class="py-4 px-6">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold ${item.status === 'dipinjam' ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700'}">
                    ${item.status === 'dipinjam' ? 'Dipinjam' : 'Kembali'}
                </span>
            </td>
        `;
        tbody.insertBefore(tr, tbody.firstChild);
        setTimeout(() => tr.classList.remove('bg-green-50'), 3000);
    }

    function playBeep() {
        try {
            const c = new (window.AudioContext || window.webkitAudioContext)();
            const o = c.createOscillator(); const g = c.createGain();
            o.connect(g); g.connect(c.destination); o.frequency.value = 800; o.type = 'sine'; g.gain.value = 0.3;
            o.start(); setTimeout(() => { o.stop(); c.close(); }, 200);
        } catch (e) {}
    }

    async function revokeCurrentToken() {
        if (!currentToken || !confirm('Batalkan QR yang sedang aktif?')) return;
        try {
            await fetch(`/qr-revoke/${currentToken}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
            currentToken = null;
            if (countdownInterval) clearInterval(countdownInterval);
            document.getElementById('qrPlaceholder').style.display = 'flex';
            document.getElementById('qrActive').style.display = 'none';
        } catch (e) {}
    }

    document.addEventListener('DOMContentLoaded', () => {
        startPolling();
        
        // Cek jika ada session aktif dari hidden HTML element
        const sessionData = document.getElementById('activeSessionData');
        if (sessionData) {
            currentToken = sessionData.dataset.token;
            showQR(
                sessionData.dataset.url, 
                sessionData.dataset.time, 
                sessionData.dataset.full
            );
        }
    });
    </script>
    @include('components.accessibility-button')
</body>
</html>


