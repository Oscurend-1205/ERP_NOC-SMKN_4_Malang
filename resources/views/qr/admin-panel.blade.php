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
        <div id="pjax-content" class="p-4 md:p-10 pt-4 md:pt-6 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                    <strong class="font-bold">Gagal!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                    <strong class="font-bold">Terjadi Kesalahan!</strong>
                    <ul class="list-disc list-inside mt-2 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">QR & Manual Peminjaman</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola sesi peminjaman real-time via QR Code atau input manual.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Manual Input Banner -->
                <div class="lg:col-span-2 bg-[#1A73E8] rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between shadow-sm text-white relative overflow-hidden">
                    <div class="flex items-center gap-6 relative z-10 w-full">
                        <span class="material-symbols-outlined text-[64px] opacity-90 hidden md:block">qr_code_scanner</span>
                        <div class="flex-grow">
                            <h3 class="text-xl font-bold mb-4">Input Peminjaman Manual</h3>
                            <button onclick="document.getElementById('loanModal').classList.remove('hidden')" class="w-full md:w-auto bg-white text-[#1A73E8] hover:bg-blue-50 font-extrabold py-3 px-8 rounded-xl transition-all flex items-center justify-center gap-2 shadow-md text-[15px] tracking-wide active:scale-95">
                                <span class="material-symbols-outlined text-[22px] font-bold">add</span>
                                Mulai Input Manual
                            </button>
                        </div>
                    </div>
                    <!-- Decorative background element -->
                    <div class="absolute right-0 top-0 bottom-0 w-64 bg-white/10 skew-x-[-20deg] transform translate-x-16"></div>
                </div>

                <!-- Quick Settings -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-center">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-gray-400">schedule</span>
                        <h3 class="font-bold text-gray-800 text-sm">Quick Settings</h3>
                    </div>
                    <label class="block text-xs font-bold text-gray-600 mb-2">Durasi Sesi</label>
                    <select id="expiryMinutes" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] outline-none transition-all cursor-pointer bg-gray-50">
                        <option value="5">5 Menit (Cepat)</option>
                        <option value="10" selected>10 Menit (Standar)</option>
                        <option value="15">15 Menit</option>
                        <option value="30">30 Menit</option>
                        <option value="60">1 Jam</option>
                    </select>
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
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden border-t-4 border-t-[#8C98CD]">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                            <span class="material-symbols-outlined text-gray-500 text-[20px]">qr_code_scanner</span>
                            <h2 class="text-sm font-bold text-gray-800">QR Session Aktif</h2>
                        </div>
                        <div class="p-8 text-center flex flex-col items-center justify-center">
                            
                            <div id="qrPlaceholder" class="py-10 text-gray-400 text-sm flex flex-col items-center w-full">
                                <span class="material-symbols-outlined text-[64px] mb-4 opacity-30">qr_code</span>
                                <p class="mb-6">Belum ada QR aktif.<br>Klik tombol di bawah untuk memulai.</p>
                                <!-- Generate QR Session Button -->
                                <button id="btnGenerateQr" onclick="generateQR()" class="flex items-center justify-center gap-2 w-full px-4 py-3.5 bg-[#4F46E5] text-white font-bold rounded-xl hover:bg-[#4338CA] transition-all shadow-md active:scale-95 text-[15px] tracking-wide">
                                    <span class="material-symbols-outlined text-[20px]">qr_code</span>
                                    Generate QR Session
                                </button>
                            </div>
                            
                            <div id="qrActive" class="hidden w-full">
                                <div id="qrImageWrapper" onclick="window.openFullQr()" class="bg-white p-4 rounded-xl inline-block shadow-sm border border-gray-100 mb-4 cursor-pointer hover:scale-[1.03] transition-transform duration-200" title="Klik untuk memperbesar"></div>
                                <div class="text-sm font-bold text-[#3F51B5]">Scan QR untuk Memulai</div>
                                
                                <div class="mt-4 p-4 bg-gray-50 rounded-xl">
                                    <div class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Waktu Tersisa</div>
                                    <div id="qrCountdown" class="text-3xl font-extrabold text-[#3F51B5] font-mono tracking-widest">00:00</div>
                                </div>

                                <div class="text-xs text-gray-500 mt-4">
                                    Berlaku sampai: <span id="qrExpiry" class="text-orange-500 font-bold"></span>
                                </div>
                                
                                <button onclick="revokeCurrentToken()" class="mt-4 text-xs font-bold text-red-600 hover:text-red-800 underline transition-colors px-4 py-2 hover:bg-red-50 rounded-lg">
                                    Batalkan Sesi Sekarang
                                </button>
                            </div>
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
                        <div class="space-y-1.5 text-left relative">
                            <label class="block text-[13px] font-bold text-gray-700">Nama Lengkap</label>
                            <input type="text" id="borrower_name_input" name="borrower_name" required placeholder="Ketik untuk mencari nama..." autocomplete="off"
                                class="w-full px-3 py-2 text-[13px] bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] transition-all placeholder:text-gray-400 outline-none">
                            
                            <!-- Autocomplete Dropdown -->
                            <div id="users_autocomplete_dropdown" class="absolute z-[110] w-full bg-white border border-gray-200 rounded-lg shadow-lg mt-1 hidden max-h-48 overflow-y-auto">
                            </div>
                        </div>

                        <!-- Kelas -->
                        <div class="space-y-1.5 text-left">
                            <label class="block text-[13px] font-bold text-gray-700">Kelas / Jurusan</label>
                            <select name="kelas" required class="w-full px-3 py-2 text-[13px] bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] transition-all text-gray-700 outline-none cursor-pointer">
                                <option value="" disabled selected>Pilih kelas atau jurusan</option>
                                @foreach($jurusans ?? [] as $jurusan)
                                    <option value="{{ $jurusan->name }}">{{ $jurusan->name }}</option>
                                @endforeach
                            </select>
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

                        <!-- ID Barang -->
                        <div class="space-y-1.5 text-left relative">
                            <label class="block text-[13px] font-bold text-gray-700">ID Barang</label>
                            <input type="text" id="item_code_input" name="item_code" required placeholder="Ketik kode barang (contoh: INV-00002)" autocomplete="off"
                                class="w-full px-3 py-2 text-[13px] bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] transition-all placeholder:text-gray-400 outline-none">
                            
                            <!-- Autocomplete Dropdown -->
                            <div id="items_code_autocomplete_dropdown" class="absolute z-[110] w-full bg-white border border-gray-200 rounded-lg shadow-lg mt-1 hidden max-h-48 overflow-y-auto">
                            </div>
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

        <!-- Fullscreen QR Modal -->
        <div id="fullQrModal" onclick="window.closeFullQr()" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/90 backdrop-blur-sm transition-opacity cursor-pointer">
            <div onclick="event.stopPropagation()" class="relative bg-white p-8 md:p-12 rounded-[2rem] shadow-2xl flex flex-col items-center animate-fade-in-up max-w-xl w-full mx-4 cursor-default">
                <!-- Close Button -->
                <button onclick="window.closeFullQr()" class="absolute -top-5 -right-5 bg-red-500 text-white p-3 rounded-full shadow-lg hover:bg-red-600 hover:scale-110 transition-all duration-200">
                    <span class="material-symbols-outlined text-[28px]">close</span>
                </button>
                
                <h2 class="text-3xl font-bold text-[#1A73E8] mb-6 text-center">Scan QR untuk Meminjam</h2>
                
                <!-- Modal QR Container -->
                <div id="modalQrWrapper" class="bg-white p-5 rounded-2xl shadow-inner border-4 border-gray-100 mb-6 flex items-center justify-center min-w-[400px] min-h-[400px]">
                    <!-- QR injected here -->
                </div>
                
                <div class="bg-gray-50 px-8 py-5 rounded-2xl w-full text-center border border-gray-100">
                    <p class="text-base text-gray-500 font-bold uppercase tracking-wider mb-2">Sisa Waktu Sesi</p>
                    <div id="modalQrCountdown" class="text-5xl font-extrabold text-[#3F51B5] font-mono tracking-widest">00:00</div>
                </div>
            </div>
        </div>
    </main>

    <script>
// Prevent multiple polling intervals on PJAX navigate
if (window.qrPollInterval) clearInterval(window.qrPollInterval);
if (window.qrCountdownInterval) clearInterval(window.qrCountdownInterval);

(function() {
    let currentToken = null;
    let lastPollTime = "{{ now()->toIso8601String() }}";

    async function ensureQRCodeLoaded() {
        if (window.QRCode) return true;
        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js';
            script.onload = resolve;
            script.onerror = () => reject(new Error('Gagal memuat pustaka QRCode'));
            document.head.appendChild(script);
        });
    }

    async function generateQR() {
        const minutes = document.getElementById('expiryMinutes').value;
        const btn = document.getElementById('btnGenerateQr');
        const originalBtnHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `<span class="material-symbols-outlined animate-spin text-[20px]">progress_activity</span> Memproses...`;

        try {
            await ensureQRCodeLoaded();

            const res = await fetch('{{ route("qr.generate") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ expiry_minutes: minutes })
            });
            const data = await res.json();

            if (data.success === true) {
                currentToken = data.token;
                
                renderQR(data.scan_url);
                renderQRModal(data.scan_url);
                startCountdown(new Date(data.expired_at_full));
                
                document.getElementById('qrPlaceholder').classList.add('hidden');
                document.getElementById('qrActive').classList.remove('hidden');

                // Start Polling
                if (window.qrPollInterval) clearInterval(window.qrPollInterval);
                window.qrPollInterval = setInterval(pollPeminjaman, 3000);
            } else {
                alert(data.message || 'Gagal membuat QR Code.');
            }
        } catch(e) {
            console.error(e);
            alert('Gagal memproses permintaan: ' + e.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalBtnHtml;
        }
    }

    function renderQR(url) {
        const wrapper = document.getElementById('qrImageWrapper');
        wrapper.innerHTML = '';
        const div = document.createElement('div');
        new QRCode(div, { text: url, width: 250, height: 250 });
        wrapper.appendChild(div);
    }

    function renderQRModal(url) {
        const modalWrapper = document.getElementById('modalQrWrapper');
        modalWrapper.innerHTML = '';
        const modalDiv = document.createElement('div');
        new QRCode(modalDiv, { text: url, width: 400, height: 400 });
        modalWrapper.appendChild(modalDiv);
    }

    function startCountdown(exp) {
        if (window.qrCountdownInterval) clearInterval(window.qrCountdownInterval);
        const countEl = document.getElementById('qrCountdown');
        const countModal = document.getElementById('modalQrCountdown');
        
        window.qrCountdownInterval = setInterval(() => {
            const diff = exp - new Date();
            if (diff <= 0) {
                clearInterval(window.qrCountdownInterval);
                const statusText = 'EXPIRED';
                countEl.textContent = statusText;
                countEl.classList.remove('text-[#3F51B5]');
                countEl.classList.add('text-red-600');
                if (countModal) {
                    countModal.textContent = statusText;
                    countModal.classList.remove('text-[#3F51B5]');
                    countModal.classList.add('text-red-600');
                }
                
                document.getElementById('qrPlaceholder').classList.remove('hidden');
                document.getElementById('qrActive').classList.add('hidden');
                if (window.qrPollInterval) clearInterval(window.qrPollInterval);
                return;
            }
            const m = Math.floor(diff / 60000), s = Math.floor((diff % 60000) / 1000);
            const formatTime = `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
            
            countEl.textContent = formatTime;
            countEl.classList.add('text-[#3F51B5]');
            countEl.classList.remove('text-red-600');
            if(countModal) {
                countModal.textContent = formatTime;
                countModal.classList.add('text-[#3F51B5]');
                countModal.classList.remove('text-red-600');
            }
        }, 1000);
    }

    function openFullQr() {
        const modal = document.getElementById('fullQrModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeFullQr() {
        const modal = document.getElementById('fullQrModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    // Tab Logic
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('text-blue-600', 'border-blue-600', 'bg-blue-50/50');
                b.classList.add('text-gray-500', 'border-transparent', 'hover:text-gray-700', 'hover:border-gray-300');
            });
            btn.classList.add('text-blue-600', 'border-blue-600', 'bg-blue-50/50');
            btn.classList.remove('text-gray-500', 'border-transparent', 'hover:text-gray-700', 'hover:border-gray-300');

            document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
            document.getElementById(btn.dataset.target).classList.remove('hidden');
        });
    });

    async function pollPeminjaman() {
        try {
            const res = await fetch(`{{ route("qr.poll") }}?since=${encodeURIComponent(lastPollTime)}`);
            const r = await res.json();
            if (r.success === true) {
                if (r.data.length > 0) {
                    r.data.forEach(p => prependPeminjamanRow(p));
                }
                lastPollTime = r.server_time;
            }
        } catch(e) {}
    }

    function prependPeminjamanRow(p) {
        const emptyRow = document.getElementById('emptyRow');
        if (emptyRow) emptyRow.classList.add('hidden');
        
        const tbody = document.getElementById('peminjamanTableBody');
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-blue-50/30 transition-colors animate-fade-in border-b border-gray-100 last:border-none';
        tr.innerHTML = `
            <td class="py-4 px-6 font-semibold text-sm text-gray-800">${p.nama_peminjam}</td>
            <td class="py-4 px-6 text-sm text-gray-600">${p.kelas}</td>
            <td class="py-4 px-6">
                <div class="font-medium text-sm text-gray-800">${p.item_name || '-'}</div>
                <code class="text-[10px] text-[#3F51B5] bg-indigo-50 px-2 py-0.5 rounded mt-0.5 inline-block">${p.item_code}</code>
            </td>
            <td class="py-4 px-6 text-xs text-gray-500 font-medium">${p.waktu_pinjam_formatted}</td>
            <td class="py-4 px-6">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold ${p.status === 'dipinjam' ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700'}">
                    ${p.status_label || (p.status === 'dipinjam' ? 'Dipinjam' : 'Dikembalikan')}
                </span>
            </td>
        `;
        tbody.insertBefore(tr, tbody.firstChild);
        setTimeout(() => tr.classList.remove('bg-blue-50/30'), 3000);
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
            if (window.qrCountdownInterval) clearInterval(window.qrCountdownInterval);
            document.getElementById('qrPlaceholder').classList.remove('hidden');
            document.getElementById('qrActive').classList.add('hidden');
        } catch (e) {}
    }
    
    // Attach to global scope for HTML onclick attributes
    window.generateQR = generateQR;
    window.openFullQr = openFullQr;
    window.closeFullQr = closeFullQr;
    window.revokeCurrentToken = revokeCurrentToken;

    // Cek jika ada session aktif dari hidden HTML element
    const sessionData = document.getElementById('activeSessionData');
    if (sessionData) {
        ensureQRCodeLoaded().then(() => {
            currentToken = sessionData.dataset.token;
            renderQR(sessionData.dataset.url);
            renderQRModal(sessionData.dataset.url);
            startCountdown(new Date(sessionData.dataset.full));
            
            document.getElementById('qrPlaceholder').classList.add('hidden');
            document.getElementById('qrActive').classList.remove('hidden');
        }).catch(e => console.error(e));
        
        if (window.qrPollInterval) clearInterval(window.qrPollInterval);
        window.qrPollInterval = setInterval(pollPeminjaman, 3000);
    } else {
        if (window.qrPollInterval) clearInterval(window.qrPollInterval);
        window.qrPollInterval = setInterval(pollPeminjaman, 5000);
    }

    // Expose functions globally for onclick handlers
    window.generateQR = generateQR;
    window.revokeCurrentToken = revokeCurrentToken;
    window.openFullQr = openFullQr;
    window.closeFullQr = closeFullQr;
    // Setup JS Autocomplete logic globally to persist across PJAX
    window.setupAutocompletes = function() {
        // Autocomplete for Nama Lengkap
        const usersData = @json($users ?? []);
        const nameInput = document.getElementById('borrower_name_input');
        const nameDropdown = document.getElementById('users_autocomplete_dropdown');

        if (nameInput && nameDropdown) {
            nameInput.addEventListener('input', function() {
                const val = this.value.toLowerCase();
                nameDropdown.innerHTML = '';
                if (!val) {
                    nameDropdown.classList.add('hidden');
                    return;
                }
                
                const matches = usersData.filter(u => u.name.toLowerCase().includes(val));
                if (matches.length > 0) {
                    matches.forEach(u => {
                        const div = document.createElement('div');
                        div.className = 'px-4 py-3 text-[13px] cursor-pointer hover:bg-[#DDF4F9] flex justify-between items-center border-b border-gray-50 last:border-0 transition-colors';
                        
                        let roleText = u.role ? ` / ${u.role}` : '';
                        div.innerHTML = `
                            <span class="font-medium text-gray-800">${u.name}${roleText}</span>
                            <span class="text-xs text-gray-500 bg-gray-50 px-2 py-0.5 rounded border border-gray-200 shadow-sm">Data User</span>
                        `;
                        
                        div.onclick = function() {
                            nameInput.value = u.name;
                            nameDropdown.classList.add('hidden');
                        };
                        nameDropdown.appendChild(div);
                    });
                    nameDropdown.classList.remove('hidden');
                } else {
                    nameDropdown.classList.add('hidden');
                }
            });

            document.addEventListener('click', function(e) {
                if (e.target !== nameInput && e.target !== nameDropdown) {
                    nameDropdown.classList.add('hidden');
                }
            });
        }

        // Autocomplete for ID Barang
        const itemsData = @json($availableItems ?? []);
        const codeInput = document.getElementById('item_code_input');
        const codeDropdown = document.getElementById('items_code_autocomplete_dropdown');

        if (codeInput && codeDropdown) {
            codeInput.addEventListener('input', function() {
                const val = this.value.toLowerCase();
                codeDropdown.innerHTML = '';
                if (!val) {
                    codeDropdown.classList.add('hidden');
                    return;
                }
                
                const matches = itemsData.filter(i => i.code.toLowerCase().includes(val) || i.name.toLowerCase().includes(val));
                if (matches.length > 0) {
                    matches.forEach(i => {
                        const div = document.createElement('div');
                        div.className = 'px-4 py-3 text-[13px] cursor-pointer hover:bg-[#E8F0FE] flex justify-between items-center border-b border-gray-50 last:border-0 transition-colors';
                        
                        div.innerHTML = `
                            <span class="font-medium text-gray-800">${i.code}</span>
                            <span class="text-[11px] text-gray-500 bg-gray-50 px-2 py-0.5 rounded border border-gray-200 shadow-sm">${i.name}</span>
                        `;
                        
                        div.onclick = function() {
                            codeInput.value = i.code;
                            codeDropdown.classList.add('hidden');
                        };
                        codeDropdown.appendChild(div);
                    });
                    codeDropdown.classList.remove('hidden');
                } else {
                    codeDropdown.classList.add('hidden');
                }
            });

            document.addEventListener('click', function(e) {
                if (e.target !== codeInput && e.target !== codeDropdown) {
                    codeDropdown.classList.add('hidden');
                }
            });
        }
    };

    // Initialize immediately
    window.setupAutocompletes();
})();
</script>
</div>
<!-- END PJAX CONTENT -->
    @vite(['resources/js/turbo-navigation.js'])
    @include('components.accessibility-button')
</body>
</html>


