@extends('layouts.app')

@section('title', 'Stasiun Peminjaman QR & Manual')

@section('content')
<div class="space-y-4">
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-2.5 rounded-xl text-xs font-semibold flex items-center justify-between" role="alert">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600 text-[18px]">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">✕</button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-2.5 rounded-xl text-xs font-semibold flex items-center justify-between" role="alert">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-rose-600 text-[18px]">error</span>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">✕</button>
        </div>
    @endif

    {{-- Header Utama Banner Card --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-xs relative overflow-hidden mb-4">
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-700 via-indigo-600 to-blue-800"></div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <!-- <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-indigo-50 border border-indigo-100 text-[#3F51B5] text-[11px] font-mono font-semibold uppercase tracking-wider mb-2">
                    <span>STASIUN PEMINJAMAN</span>
                    <span class="w-1 h-1 rounded-full bg-indigo-400"></span>
                    <span>SISTEM ASET NOC SMKN 4 MALANG</span>
                </div> -->
                <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight flex items-center gap-2">
                    <!-- <span class="material-symbols-outlined text-[#3F51B5] text-[28px]">qr_code_scanner</span> -->
                    Peminjaman QR & Input Manual
                </h2>
            </div>
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <div class="flex items-center gap-1.5 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs">
                    <span class="text-gray-500 font-semibold">Durasi QR:</span>
                    <select id="expiryMinutes" class="bg-transparent border-none p-0 text-xs font-bold text-gray-800 focus:ring-0 cursor-pointer outline-none">
                        <option value="5">5 Menit</option>
                        <option value="10" selected>10 Menit</option>
                        <option value="15">15 Menit</option>
                        <option value="30">30 Menit</option>
                        <option value="60">1 Jam</option>
                    </select>
                </div>
                <button type="button" onclick="document.getElementById('loanModal').classList.remove('hidden')" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-[#3F51B5] hover:bg-[#3949AB] text-white font-bold rounded-xl transition-all shadow-sm text-xs cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">edit_square</span>
                    <span>+ Input Peminjaman Manual</span>
                </button>
            </div>
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

            <!-- 2-Column Balanced Workstation Area (Fits directly in viewport) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 flex-1 min-h-0 items-stretch">
                
                <!-- ========================================================= -->
                <!-- LEFT COLUMN: QR Code Station (5 Cols) -->
                <!-- ========================================================= -->
                <div class="lg:col-span-5 flex flex-col">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col flex-1 h-full">
                        <div class="px-5 py-3.5 bg-gray-50/75 border-b border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-blue-600 text-[18px]">qr_code_2</span>
                                <h2 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Sesi QR Peminjaman</h2>
                            </div>
                            <span id="qrStatusBadge" class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-gray-200 text-gray-600">
                                Belum Aktif
                            </span>
                        </div>

                        <!-- Card Content Container -->
                        <div class="p-5 flex-1 flex flex-col items-center justify-center text-center">
                            
                            <!-- State 1: Inactive Placeholder -->
                            <div id="qrPlaceholder" class="py-6 flex flex-col items-center justify-center w-full">
                                <div class="w-20 h-20 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                                    <span class="material-symbols-outlined text-[44px]">qr_code</span>
                                </div>
                                <h3 class="text-sm font-bold text-gray-800">Sesi QR Belum Dibuat</h3>
                                <p class="text-xs text-gray-400 mt-1 max-w-xs leading-relaxed mb-6">
                                    Buat sesi QR Code baru untuk memfasilitasi peminjaman mandiri via smartphone siswa.
                                </p>
                                <button id="btnGenerateQr" onclick="generateQR()" class="inline-flex items-center justify-center gap-2 w-full max-w-xs px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md transition-all active:scale-95 text-xs tracking-wide cursor-pointer">
                                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                                    <span>Generate Sesi QR Sekarang</span>
                                </button>
                            </div>

                            <!-- State 2: Active QR Session -->
                            <div id="qrActive" class="hidden w-full flex flex-col items-center">
                                
                                <!-- QR Display Box -->
                                <div id="qrImageWrapper" onclick="window.openFullQr()" class="bg-white p-3 rounded-2xl shadow-sm border-2 border-gray-100 mb-3 cursor-pointer hover:border-blue-400 hover:scale-[1.02] transition-all" title="Klik untuk mode layar penuh (Proyektor)">
                                    <!-- QR rendered here -->
                                </div>

                                <!-- Countdown Strip -->
                                <div class="w-full bg-blue-50/60 border border-blue-100 rounded-xl p-2.5 flex items-center justify-between mb-3">
                                    <div class="text-left">
                                        <div class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Sisa Waktu Sesi</div>
                                        <div class="text-[11px] text-gray-400 mt-0.5">Berlaku s/d: <span id="qrExpiry" class="text-blue-700 font-bold"></span></div>
                                    </div>
                                    <div id="qrCountdown" class="text-2xl font-black text-blue-700 font-mono tracking-wider">00:00</div>
                                </div>

                                <!-- Quick Copy Link Bar -->
                                <div class="w-full flex items-center gap-1.5 bg-gray-50 border border-gray-200 rounded-xl p-1.5 mb-3">
                                    <input type="text" id="scanUrlInput" readonly class="bg-transparent border-none text-[11px] text-gray-600 font-mono px-2 py-0.5 flex-1 focus:ring-0 select-all truncate" value=""/>
                                    <button type="button" id="btnCopyUrl" onclick="window.copyScanUrl()" class="px-2.5 py-1 bg-white border border-gray-200 text-gray-700 hover:text-blue-600 rounded-lg text-[11px] font-bold transition-all shadow-2xs flex-shrink-0 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">content_copy</span>
                                        <span>Salin</span>
                                    </button>
                                </div>

                                <!-- Action Buttons Row -->
                                <div class="w-full grid grid-cols-2 gap-2">
                                    <button onclick="window.openFullQr()" class="px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl text-xs font-bold transition-colors flex items-center justify-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">fullscreen</span>
                                        <span>Layar Penuh</span>
                                    </button>
                                    <button onclick="revokeCurrentToken()" class="px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-bold transition-colors flex items-center justify-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">cancel</span>
                                        <span>Batalkan Sesi</span>
                                    </button>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                <!-- ========================================================= -->
                <!-- RIGHT COLUMN: Live Feed Peminjaman Real-Time (7 Cols) -->
                <!-- ========================================================= -->
                <div class="lg:col-span-7 flex flex-col min-h-0">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col flex-1 h-full">
                        
                        <!-- Table Header -->
                        <div class="px-5 py-3.5 bg-gray-50/75 border-b border-gray-100 flex items-center justify-between flex-shrink-0">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-indigo-600 text-[18px]">sensors</span>
                                <h2 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Peminjaman Masuk (Live Feed)</h2>
                            </div>
                            <div class="flex items-center gap-2">
                                <span id="feedCountBadge" class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700">
                                    {{ count($recentPeminjaman) }} Terkini
                                </span>
                            </div>
                        </div>

                        <!-- Scrollable Table Body Container (Optimized height, no page scroll) -->
                        <div class="overflow-x-auto flex-1 overflow-y-auto max-h-[500px]">
                            <table class="w-full text-left border-collapse">
                                <thead class="sticky top-0 bg-gray-100/90 backdrop-blur-sm z-10 border-b border-gray-200">
                                    <tr>
                                        <th class="py-2.5 px-4 text-[11px] font-bold text-gray-700 uppercase tracking-wider">Peminjam</th>
                                        <th class="py-2.5 px-4 text-[11px] font-bold text-gray-700 uppercase tracking-wider">Kelas</th>
                                        <th class="py-2.5 px-4 text-[11px] font-bold text-gray-700 uppercase tracking-wider">Perangkat</th>
                                        <th class="py-2.5 px-4 text-[11px] font-bold text-gray-700 uppercase tracking-wider">Waktu</th>
                                        <th class="py-2.5 px-4 text-[11px] font-bold text-gray-700 uppercase tracking-wider text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="peminjamanTableBody" class="divide-y divide-gray-100 text-xs">
                                    @forelse($recentPeminjaman as $p)
                                    <tr class="hover:bg-gray-50/80 transition-colors">
                                        <td class="py-2.5 px-4 font-semibold text-gray-900">{{ $p->nama_peminjam }}</td>
                                        <td class="py-2.5 px-4 text-gray-600 font-medium">{{ $p->kelas }}</td>
                                        <td class="py-2.5 px-4">
                                            <div class="font-bold text-gray-800">{{ $p->item->name ?? '-' }}</div>
                                            <code class="text-[10px] text-blue-700 bg-blue-50 px-1.5 py-0.5 rounded mt-0.5 inline-block font-mono">{{ $p->item_code }}</code>
                                        </td>
                                        <td class="py-2.5 px-4 text-gray-500 font-mono text-[11px] whitespace-nowrap">
                                            {{ $p->waktu_pinjam->format('H:i') }} WIB
                                        </td>
                                        <td class="py-2.5 px-4 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold {{ $p->status === 'dipinjam' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                                {{ $p->status_label ?? ($p->status === 'dipinjam' ? 'Dipinjam' : 'Dikembalikan') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr id="emptyRow">
                                        <td colspan="5" class="py-16 text-center text-gray-400">
                                            <span class="material-symbols-outlined text-[44px] mb-2 opacity-20 block">inbox</span>
                                            <div class="font-semibold text-gray-600">Belum ada aktivitas peminjaman</div>
                                            <div class="text-[11px] mt-0.5">Data akan muncul secara real-time saat barcode di-scan.</div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </main>

    <!-- Modal Input Pinjaman Manual -->
    <div id="loanModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-xs transition-opacity" onclick="document.getElementById('loanModal').classList.add('hidden')"></div>
        
        <div class="relative w-full max-w-[540px] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-white">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-600 text-[20px]">edit_note</span>
                    <h2 class="text-base font-bold text-gray-900">Input Peminjaman Manual</h2>
                </div>
                <button onclick="document.getElementById('loanModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-700 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>

            <form action="{{ route('movements.loan') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <div class="px-6 py-4 space-y-3.5 overflow-y-auto">
                    <!-- Nama Lengkap -->
                    <div class="space-y-1 text-left relative">
                        <label class="block text-xs font-bold text-gray-700">Nama Lengkap Peminjam</label>
                        <input type="text" id="borrower_name_input" name="borrower_name" required placeholder="Ketik nama siswa atau guru..." autocomplete="off"
                            class="w-full px-3 py-2 text-xs bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all outline-none">
                        <div id="users_autocomplete_dropdown" class="absolute z-[110] w-full bg-white border border-gray-200 rounded-xl shadow-lg mt-1 hidden max-h-44 overflow-y-auto"></div>
                    </div>

                    <!-- Kelas / Jurusan -->
                    <div class="space-y-1 text-left">
                        <label class="block text-xs font-bold text-gray-700">Kelas / Jurusan</label>
                        <select name="kelas" required class="w-full px-3 py-2 text-xs bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all text-gray-700 outline-none cursor-pointer">
                            <option value="" disabled selected>Pilih kelas atau unit jurusan...</option>
                            <option value="Guru / Instruktur">Guru / Instruktur</option>
                            <option value="X TKJ 1">X TKJ 1</option>
                            <option value="X TKJ 2">X TKJ 2</option>
                            <option value="XI TKJ 1">XI TKJ 1</option>
                            <option value="XI TKJ 2">XI TKJ 2</option>
                            <option value="XII TKJ 1">XII TKJ 1</option>
                            <option value="XII TKJ 2">XII TKJ 2</option>
                            <option value="XI RPL 1">XI RPL 1</option>
                            <option value="XII RPL 1">XII RPL 1</option>
                            <option value="XI SIJA">XI SIJA</option>
                            <option value="XII SIJA">XII SIJA</option>
                            @foreach($jurusans ?? [] as $jurusan)
                                <option value="{{ $jurusan->name }}">{{ $jurusan->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- No HP -->
                    <div class="space-y-1 text-left">
                        <label class="block text-xs font-bold text-gray-700">Nomor Kontak / WhatsApp (Opsional)</label>
                        <input type="text" id="borrower_phone_input" name="borrower_phone" placeholder="08..." 
                            class="w-full px-3 py-2 text-xs bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all outline-none">
                    </div>

                    <!-- Nama Barang -->
                    <div class="space-y-1 text-left">
                        <label class="block text-xs font-bold text-gray-700">Nama Perangkat Aset</label>
                        @php
                            $groupedItems = collect($availableItems ?? [])->groupBy('name');
                        @endphp
                        <select id="item_name_select" required class="w-full px-3 py-2 text-xs bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all text-gray-700 outline-none cursor-pointer">
                            <option value="" disabled selected>Pilih perangkat...</option>
                            @foreach($groupedItems as $name => $items)
                                <option value="{{ $name }}">{{ $name }} (Tersedia: {{ $items->count() }} unit)</option>
                            @endforeach
                        </select>
                        <input type="hidden" id="hidden_item_id" name="item_id" required>
                    </div>

                    <!-- ID Barang -->
                    <div class="space-y-1 text-left relative">
                        <label class="block text-xs font-bold text-gray-700">Kode Barcode / Nomor Aset</label>
                        <input type="text" id="item_code_input" name="item_code" required placeholder="Contoh: RTR-MKT-0001" autocomplete="off"
                            class="w-full px-3 py-2 text-xs bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all outline-none font-mono">
                        <div id="items_code_autocomplete_dropdown" class="absolute z-[110] w-full bg-white border border-gray-200 rounded-xl shadow-lg mt-1 hidden max-h-44 overflow-y-auto"></div>
                    </div>

                    <!-- Tanggal Peminjaman -->
                    <div class="space-y-1 text-left">
                        <label class="block text-xs font-bold text-gray-700">Tanggal Transaksi Pinjam</label>
                        <input type="date" name="movement_date" required value="{{ date('Y-m-d') }}"
                            class="w-full px-3 py-2 text-xs bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all text-gray-700 outline-none">
                    </div>
                </div>

                <div class="px-5 py-3.5 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-2.5 mt-auto">
                    <button type="button" onclick="document.getElementById('loanModal').classList.add('hidden')" 
                            class="px-4 py-2 text-xs font-semibold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-5 py-2 text-xs font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Fullscreen QR Modal (Projector/TV Mode) -->
    <div id="fullQrModal" onclick="window.closeFullQr()" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/90 backdrop-blur-sm transition-opacity cursor-pointer">
        <div onclick="event.stopPropagation()" class="relative bg-white p-6 md:p-8 rounded-3xl shadow-2xl flex flex-col items-center max-w-lg w-full mx-4 cursor-default">
            <button onclick="window.closeFullQr()" class="absolute top-4 right-4 bg-gray-100 hover:bg-gray-200 text-gray-700 p-2 rounded-full transition-all">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
            
            <h2 class="text-xl font-black text-gray-900 mb-1 text-center">Scan QR Peminjaman Mandiri</h2>
            <p class="text-xs text-gray-500 mb-5 text-center">Buka kamera ponsel Anda untuk memproses formulir peminjaman</p>
            
            <div id="modalQrWrapper" class="bg-white p-4 rounded-2xl shadow-inner border-2 border-gray-100 mb-5 flex items-center justify-center min-w-[320px] min-h-[320px]">
                <!-- QR injected here -->
            </div>
            
            <div class="bg-gray-50 px-6 py-3 rounded-2xl w-full text-center border border-gray-100 flex items-center justify-between">
                <span class="text-xs text-gray-500 font-bold uppercase tracking-wider">Sisa Waktu Sesi:</span>
                <div id="modalQrCountdown" class="text-2xl font-black text-blue-600 font-mono tracking-widest">00:00</div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
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
        btn.innerHTML = `<span class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span> Memproses...`;

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
                
                const scanInput = document.getElementById('scanUrlInput');
                if (scanInput) scanInput.value = data.scan_url;

                document.getElementById('qrPlaceholder').classList.add('hidden');
                document.getElementById('qrActive').classList.remove('hidden');
                
                const statusBadge = document.getElementById('qrStatusBadge');
                if (statusBadge) {
                    statusBadge.textContent = 'AKTIF (' + minutes + ' Menit)';
                    statusBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800';
                }

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
        new QRCode(div, { text: url, width: 210, height: 210 });
        wrapper.appendChild(div);
    }

    function renderQRModal(url) {
        const modalWrapper = document.getElementById('modalQrWrapper');
        modalWrapper.innerHTML = '';
        const modalDiv = document.createElement('div');
        new QRCode(modalDiv, { text: url, width: 300, height: 300 });
        modalWrapper.appendChild(modalDiv);
    }

    function startCountdown(exp) {
        if (window.qrCountdownInterval) clearInterval(window.qrCountdownInterval);
        const countEl = document.getElementById('qrCountdown');
        const countModal = document.getElementById('modalQrCountdown');
        const expiryEl = document.getElementById('qrExpiry');
        
        if (expiryEl) {
            const h = String(exp.getHours()).padStart(2, '0');
            const m = String(exp.getMinutes()).padStart(2, '0');
            expiryEl.textContent = h + ':' + m + ' WIB';
        }

        window.qrCountdownInterval = setInterval(() => {
            const diff = exp - new Date();
            if (diff <= 0) {
                clearInterval(window.qrCountdownInterval);
                countEl.textContent = 'EXPIRED';
                countEl.classList.add('text-red-600');
                if (countModal) {
                    countModal.textContent = 'EXPIRED';
                    countModal.classList.add('text-red-600');
                }
                
                document.getElementById('qrPlaceholder').classList.remove('hidden');
                document.getElementById('qrActive').classList.add('hidden');
                
                const statusBadge = document.getElementById('qrStatusBadge');
                if (statusBadge) {
                    statusBadge.textContent = 'EXPIRED';
                    statusBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-800';
                }

                if (window.qrPollInterval) clearInterval(window.qrPollInterval);
                return;
            }
            const m = Math.floor(diff / 60000), s = Math.floor((diff % 60000) / 1000);
            const formatTime = `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
            
            countEl.textContent = formatTime;
            if (countModal) countModal.textContent = formatTime;
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

    function copyScanUrl() {
        const url = document.getElementById('scanUrlInput')?.value;
        if (url) {
            navigator.clipboard.writeText(url).then(() => {
                const btn = document.getElementById('btnCopyUrl');
                if (btn) {
                    const oldHtml = btn.innerHTML;
                    btn.innerHTML = '<span class="material-symbols-outlined text-[14px]">check</span> Disalin!';
                    setTimeout(() => { btn.innerHTML = oldHtml; }, 2000);
                }
            });
        }
    }

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
        tr.className = 'hover:bg-blue-50/40 transition-colors animate-fade-in border-b border-gray-100 last:border-none';
        tr.innerHTML = `
            <td class="py-2.5 px-4 font-semibold text-gray-900">${p.nama_peminjam}</td>
            <td class="py-2.5 px-4 text-gray-600 font-medium">${p.kelas}</td>
            <td class="py-2.5 px-4">
                <div class="font-bold text-gray-800">${p.item_name || '-'}</div>
                <code class="text-[10px] text-blue-700 bg-blue-50 px-1.5 py-0.5 rounded mt-0.5 inline-block font-mono">${p.item_code}</code>
            </td>
            <td class="py-2.5 px-4 text-gray-500 font-mono text-[11px] whitespace-nowrap">${p.waktu_pinjam} WIB</td>
            <td class="py-2.5 px-4 text-center">
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold ${p.status === 'dipinjam' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800'}">
                    ${p.status === 'dipinjam' ? 'Dipinjam' : 'Dikembalikan'}
                </span>
            </td>
        `;
        tbody.insertBefore(tr, tbody.firstChild);
    }

    async function revokeCurrentToken() {
        if (!currentToken) return;
        if (!confirm('Batalkan sesi QR yang sedang aktif?')) return;
        try {
            await fetch(`/qr-revoke/${currentToken}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
            currentToken = null;
            if (window.qrCountdownInterval) clearInterval(window.qrCountdownInterval);
            document.getElementById('qrPlaceholder').classList.remove('hidden');
            document.getElementById('qrActive').classList.add('hidden');
            const statusBadge = document.getElementById('qrStatusBadge');
            if (statusBadge) {
                statusBadge.textContent = 'Dibatalkan';
                statusBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-gray-200 text-gray-600';
            }
        } catch (e) {}
    }
    
    // Attach to window
    window.generateQR = generateQR;
    window.openFullQr = openFullQr;
    window.closeFullQr = closeFullQr;
    window.revokeCurrentToken = revokeCurrentToken;
    window.copyScanUrl = copyScanUrl;

    // Check existing active session
    const sessionData = document.getElementById('activeSessionData');
    if (sessionData) {
        ensureQRCodeLoaded().then(() => {
            currentToken = sessionData.dataset.token;
            renderQR(sessionData.dataset.url);
            renderQRModal(sessionData.dataset.url);
            startCountdown(new Date(sessionData.dataset.full));
            
            const scanInput = document.getElementById('scanUrlInput');
            if (scanInput) scanInput.value = sessionData.dataset.url;

            document.getElementById('qrPlaceholder').classList.add('hidden');
            document.getElementById('qrActive').classList.remove('hidden');
            
            const statusBadge = document.getElementById('qrStatusBadge');
            if (statusBadge) {
                statusBadge.textContent = 'SESI AKTIF';
                statusBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800';
            }
        }).catch(e => console.error(e));
        
        if (window.qrPollInterval) clearInterval(window.qrPollInterval);
        window.qrPollInterval = setInterval(pollPeminjaman, 3000);
    } else {
        if (window.qrPollInterval) clearInterval(window.qrPollInterval);
        window.qrPollInterval = setInterval(pollPeminjaman, 5000);
    }

    // Setup Autocomplete for modal
    window.setupAutocompletes = function() {
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
                        div.className = 'px-3.5 py-2 text-xs cursor-pointer hover:bg-blue-50 flex justify-between items-center border-b border-gray-50 last:border-0';
                        div.innerHTML = `<span class="font-medium text-gray-800">${u.name}</span><span class="text-[10px] text-gray-400 font-mono">${u.role || ''}</span>`;
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

        const itemsData = @json($availableItems ?? []);
        const codeInput = document.getElementById('item_code_input');
        const codeDropdown = document.getElementById('items_code_autocomplete_dropdown');
        const nameSelect = document.getElementById('item_name_select');
        const hiddenItemId = document.getElementById('hidden_item_id');

        if (nameSelect && codeInput) {
            nameSelect.addEventListener('change', function() {
                const selectedName = this.value;
                const firstAvailable = itemsData.find(i => i.name === selectedName);
                if (firstAvailable) {
                    codeInput.value = firstAvailable.code;
                    if (hiddenItemId) hiddenItemId.value = firstAvailable.id;
                } else {
                    codeInput.value = '';
                    if (hiddenItemId) hiddenItemId.value = '';
                }
            });
        }

        if (codeInput && codeDropdown) {
            function triggerAutocomplete(val) {
                val = val.toLowerCase();
                codeDropdown.innerHTML = '';
                let matches = itemsData;
                if (nameSelect && nameSelect.value) {
                    matches = matches.filter(i => i.name === nameSelect.value);
                }
                if (val) {
                    matches = matches.filter(i => i.code.toLowerCase().includes(val) || i.name.toLowerCase().includes(val));
                }
                if (matches.length > 0) {
                    matches.forEach(i => {
                        const div = document.createElement('div');
                        div.className = 'px-3.5 py-2 text-xs cursor-pointer hover:bg-blue-50 flex justify-between items-center border-b border-gray-50 last:border-0';
                        div.innerHTML = `<span class="font-mono font-bold text-blue-700">${i.code}</span><span class="text-[10px] text-gray-500">${i.name}</span>`;
                        div.onclick = function() {
                            codeInput.value = i.code;
                            if (hiddenItemId) hiddenItemId.value = i.id;
                            if (nameSelect) nameSelect.value = i.name;
                            codeDropdown.classList.add('hidden');
                        };
                        codeDropdown.appendChild(div);
                    });
                    codeDropdown.classList.remove('hidden');
                } else {
                    codeDropdown.classList.add('hidden');
                }
            }

            codeInput.addEventListener('focus', function() { triggerAutocomplete(this.value); });
            codeInput.addEventListener('input', function() { triggerAutocomplete(this.value); });
            document.addEventListener('click', function(e) {
                if (e.target !== codeInput && e.target !== codeDropdown) {
                    codeDropdown.classList.add('hidden');
                }
            });
        }
    };

    window.setupAutocompletes();
})();
</script>
@endpush
</div>
@endsection

