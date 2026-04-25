@extends('layouts.app')

@section('title', 'QR Lending Panel')

@section('content')
<div class="page-header">
    <div>
        <h2>QR Peminjaman</h2>
        <p>Kelola sesi peminjaman barang real-time via QR Code</p>
    </div>
    <button id="btnGenerateQr" onclick="generateQR()" class="btn btn-primary">
        <i class="bi bi-qr-code"></i> Generate QR Session
    </button>
</div>

@if($activeSessions->count() > 0)
    @php 
        $s = $activeSessions->first(); 
        $baseUrl = rtrim(env('APP_URL', url('/')), '/');
        $scanUrl = $baseUrl . "/scan/{$s->token}";
    @endphp
    <div id="activeSessionData" 
         data-token="{{ $s->token }}" 
         data-url="{{ $scanUrl }}" 
         data-time="{{ $s->expired_at->format('H:i') }}" 
         data-full="{{ $s->expired_at->toIso8601String() }}" 
         style="display: none;"></div>
@endif

<div style="display: grid; grid-template-columns: 350px 1fr; gap: 20px; align-items: start;">
    {{-- LEFT COLUMN: QR & Settings --}}
    <div style="display: flex; flex-direction: column; gap: 20px;">
        {{-- QR Card --}}
        <div class="card" style="border-top: 4px solid var(--primary);">
            <div class="card-header" style="padding: 15px 20px;">
                <h2 style="font-size: 14px;"><i class="bi bi-qr-code-scan"></i> QR Session Aktif</h2>
            </div>
            <div class="card-body" style="text-align: center; padding: 30px 20px;">
                <div id="qrPlaceholder" style="color: var(--text-muted); font-size: 13px; padding: 40px 0;">
                    <i class="bi bi-qr-code" style="font-size: 48px; display: block; margin-bottom: 10px; opacity: 0.3;"></i>
                    Belum ada QR aktif.<br>Klik tombol di atas untuk memulai.
                </div>
                
                <div id="qrActive" class="hidden">
                    <div id="qrImageWrapper" style="margin-bottom: 20px; padding: 15px; background: white; border-radius: 12px; display: inline-block; box-shadow: var(--shadow-md); border: 1px solid var(--border-color);"></div>
                    <div style="font-size: 14px; font-weight: 700; color: var(--primary);">Scan QR untuk Memulai</div>
                    
                    <div style="margin-top: 15px; padding: 10px; background: var(--bg-body); border-radius: 8px;">
                        <div style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Waktu Tersisa</div>
                        <div id="qrCountdown" style="font-size: 28px; font-weight: 800; color: var(--primary); font-family: monospace;">00:00</div>
                    </div>

                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 15px;">
                        Berlaku sampai: <span id="qrExpiry" style="color: var(--warning); font-weight: 700;"></span>
                    </div>
                    
                    <button onclick="revokeCurrentToken()" class="btn" style="background: none; border: none; color: var(--danger); font-size: 12px; font-weight: 600; text-decoration: underline; margin-top: 15px; cursor: pointer;">
                        Batalkan Sesi Sekarang
                    </button>
                </div>
            </div>
        </div>

        {{-- Settings Card --}}
        <div class="card">
            <div class="card-header" style="padding: 12px 20px;">
                <h2 style="font-size: 13px;"><i class="bi bi-gear"></i> Pengaturan Sesi</h2>
            </div>
            <div class="card-body" style="padding: 15px 20px;">
                <div class="form-group">
                    <label style="font-size: 11px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 8px; text-transform: uppercase;">DURASI BERLAKU</label>
                    <select id="expiryMinutes" style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 14px; outline: none; background: white; cursor: pointer;">
                        <option value="5">5 Menit (Cepat)</option>
                        <option value="10" selected>10 Menit (Standar)</option>
                        <option value="15">15 Menit</option>
                        <option value="30">30 Menit</option>
                        <option value="60">1 Jam</option>
                    </select>
                </div>
                <p style="font-size: 11px; color: var(--text-muted); margin-top: 10px; line-height: 1.4;">
                    Sesi akan otomatis berakhir setelah waktu habis untuk keamanan.
                </p>
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN: Live Feed List --}}
    <div class="card" style="height: 100%; min-height: 500px;">
        <div class="card-header" style="background: #fafafa;">
            <h2><i class="bi bi-broadcast" style="color: var(--primary);"></i> Peminjaman Masuk (Live Feed)</h2>
            <div id="liveIndicator" style="display: flex; align-items: center; gap: 8px; font-size: 11px; font-weight: 700; color: var(--success);">
                <span class="animate-pulse" style="width: 8px; height: 8px; background: var(--success); border-radius: 50%; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);"></span>
                TERHUBUNG
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="position: sticky; top: 0; background: white; z-index: 10; box-shadow: 0 1px 0 var(--border-color);">
                        <tr>
                            <th style="padding: 15px 20px; text-align: left; font-size: 12px;">Peminjam</th>
                            <th style="padding: 15px 20px; text-align: left; font-size: 12px;">Kelas</th>
                            <th style="padding: 15px 20px; text-align: left; font-size: 12px;">Barang</th>
                            <th style="padding: 15px 20px; text-align: left; font-size: 12px;">Waktu</th>
                            <th style="padding: 15px 20px; text-align: left; font-size: 12px;">Status</th>
                        </tr>
                    </thead>
                    <tbody id="peminjamanTableBody">
                        @forelse($recentPeminjaman as $p)
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 15px 20px; font-weight: 600;">{{ $p->nama_peminjam }}</td>
                            <td style="padding: 15px 20px;">{{ $p->kelas }}</td>
                            <td style="padding: 15px 20px;">
                                <div style="font-weight: 500; font-size: 13px;">{{ $p->item->name ?? '-' }}</div>
                                <code style="font-size: 10px; color: var(--primary); background: var(--primary-light); padding: 2px 4px; border-radius: 4px;">{{ $p->item_code }}</code>
                            </td>
                            <td style="padding: 15px 20px; font-size: 12px; color: var(--text-muted);">{{ $p->waktu_pinjam->format('H:i:s') }}</td>
                            <td style="padding: 15px 20px;">
                                <span class="badge {{ $p->status === 'dipinjam' ? 'badge-warning' : 'badge-success' }}" style="font-size: 10px; padding: 4px 10px; border-radius: 20px;">
                                    {{ $p->status_label }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyRow">
                            <td colspan="5" style="text-align: center; padding: 100px 40px; color: var(--text-muted);">
                                <i class="bi bi-inbox" style="font-size: 48px; display: block; margin-bottom: 15px; opacity: 0.2;"></i>
                                <div style="font-weight: 600;">Belum ada peminjaman</div>
                                <div style="font-size: 12px;">Data akan muncul otomatis saat siswa melakukan scan.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

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
    btn.innerHTML = '<i class="bi bi-hourglass-split animate-spin"></i> Generating...';

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
        btn.innerHTML = '<i class="bi bi-qr-code"></i> Generate QR Session';
    }
}

function showQR(url, time, full) {
    document.getElementById('qrPlaceholder').style.display = 'none';
    document.getElementById('qrActive').style.display = 'block';
    document.getElementById('qrExpiry').textContent = time;

    const wrapper = document.getElementById('qrImageWrapper');
    wrapper.innerHTML = '';
    const div = document.createElement('div');
    wrapper.appendChild(div);
    new QRCode(div, { text: url, width: 200, height: 200, colorDark: "#005bbf", colorLight: "#ffffff", correctLevel: QRCode.CorrectLevel.H });
    startCountdown(new Date(full));
}

function startCountdown(exp) {
    if (countdownInterval) clearInterval(countdownInterval);
    countdownInterval = setInterval(() => {
        const diff = exp - new Date();
        if (diff <= 0) {
            clearInterval(countdownInterval);
            document.getElementById('qrCountdown').textContent = 'EXPIRED';
            document.getElementById('qrCountdown').style.color = 'var(--danger)';
            currentToken = null;
            return;
        }
        const m = Math.floor(diff / 60000), s = Math.floor((diff % 60000) / 1000);
        document.getElementById('qrCountdown').textContent = `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
    }, 1000);
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
    tr.style.borderBottom = '1px solid var(--border-color)';
    tr.style.background = '#f0fdf4';
    tr.style.transition = 'background 2s ease';
    tr.innerHTML = `
        <td style="padding: 15px 20px; font-weight: 600;">${item.nama_peminjam}</td>
        <td style="padding: 15px 20px;">${item.kelas}</td>
        <td style="padding: 15px 20px;">
            <div style="font-weight: 500; font-size: 13px;">${item.item_name}</div>
            <code style="font-size: 10px; color: var(--primary); background: var(--primary-light); padding: 2px 4px; border-radius: 4px;">${item.item_code}</code>
        </td>
        <td style="padding: 15px 20px; font-size: 12px; color: var(--text-muted);">${item.waktu_pinjam.split(' ')[1]}</td>
        <td style="padding: 15px 20px;"><span class="badge ${item.status === 'dipinjam' ? 'badge-warning' : 'badge-success'}" style="font-size: 10px; padding: 4px 10px; border-radius: 20px;">${item.status === 'dipinjam' ? 'Dipinjam' : 'Kembali'}</span></td>
    `;
    tbody.insertBefore(tr, tbody.firstChild);
    setTimeout(() => tr.style.background = 'transparent', 3000);
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
        document.getElementById('qrPlaceholder').style.display = 'block';
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

<style>
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .hidden { display: none; }
</style>
@endsection
