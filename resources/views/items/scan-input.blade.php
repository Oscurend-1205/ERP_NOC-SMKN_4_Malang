@extends('layouts.app')

@section('title', 'Scan & Tambah Barang')

@section('content')
<div class="page-header">
    <div>
        <h2>Scan & Tambah Barang</h2>
        <p>Gunakan kamera untuk scan stiker QR lalu lengkapi data barang</p>
    </div>
    <a href="{{ route('items.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="grid-2" style="grid-template-columns: 1fr 1.5fr;">
    {{-- Scanner Section --}}
    <div class="card">
        <div class="card-header" style="background: var(--accent); color: white;">
            <h2><i class="bi bi-camera" style="color: var(--info);"></i> Camera Scanner</h2>
            <div id="scannerStatus" style="font-size: 11px; font-weight: 700; color: var(--success); display: flex; align-items: center; gap: 5px;">
                <span style="width: 8px; height: 8px; background: var(--success); border-radius: 50%;"></span> AKTIF
            </div>
        </div>
        <div class="card-body" style="padding: 0; background: #000; position: relative; min-height: 350px;">
            <div id="reader" style="width: 100%; height: 350px; overflow: hidden;"></div>
            <div style="position: absolute; inset: 0; border: 40px solid rgba(0,0,0,0.5); pointer-events: none; display: flex; align-items: center; justify-content: center;">
                <div style="width: 180px; height: 180px; border: 2px solid rgba(255,255,255,0.4); border-radius: 12px; position: relative;">
                    <div style="position: absolute; top: -2px; left: -2px; width: 20px; height: 20px; border-top: 4px solid var(--primary); border-left: 4px solid var(--primary); border-radius: 4px 0 0 0;"></div>
                    <div style="position: absolute; top: -2px; right: -2px; width: 20px; height: 20px; border-top: 4px solid var(--primary); border-right: 4px solid var(--primary); border-radius: 0 4px 0 0;"></div>
                    <div style="position: absolute; bottom: -2px; left: -2px; width: 20px; height: 20px; border-bottom: 4px solid var(--primary); border-left: 4px solid var(--primary); border-radius: 0 0 0 4px;"></div>
                    <div style="position: absolute; bottom: -2px; right: -2px; width: 20px; height: 20px; border-bottom: 4px solid var(--primary); border-right: 4px solid var(--primary); border-radius: 0 0 4px 0;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Section --}}
    <div class="card">
        <div class="card-header">
            <h2><i class="bi bi-pencil-square" style="color: var(--primary);"></i> Form Input Barang</h2>
        </div>
        <div class="card-body">
            <form id="addItemForm" onsubmit="submitItem(event)">
                @csrf
                
                <div id="scanAlert" style="background: var(--primary-light); color: var(--primary-dark); padding: 12px; border-radius: 8px; font-size: 12px; font-weight: 600; margin-bottom: 20px; display: none;">
                    <i class="bi bi-check-circle-fill"></i> QR Code Terdeteksi! Silakan lengkapi data.
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="font-size: 11px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 5px;">KODE BARANG (DARI SCAN)</label>
                    <input type="text" id="code" name="code" readonly required placeholder="Scan stiker QR untuk mengisi..." 
                        style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-body); font-family: monospace; font-weight: 700; color: var(--primary);">
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="font-size: 11px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 5px;">NAMA BARANG</label>
                    <input type="text" id="name" name="name" required disabled placeholder="Contoh: Monitor LG 24'"
                        style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); outline: none;">
                </div>

                <div class="grid-2" style="grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div class="form-group">
                        <label style="font-size: 11px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 5px;">KATEGORI</label>
                        <select id="category_id" name="category_id" required disabled style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); background: white;">
                            <option value="">-- Pilih --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="font-size: 11px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 5px;">KONDISI</label>
                        <select id="condition" name="condition" required disabled style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); background: white;">
                            <option value="baik">Baik</option>
                            <option value="rusak_ringan">Rusak Ringan</option>
                            <option value="rusak_berat">Rusak Berat</option>
                            <option value="hilang">Hilang</option>
                        </select>
                    </div>
                </div>

                <div class="grid-2" style="grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label style="font-size: 11px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 5px;">TANGGAL MASUK</label>
                        <input type="date" id="purchase_date" name="purchase_date" required disabled value="{{ date('Y-m-d') }}"
                            style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color);">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 11px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 5px;">LOKASI LAB</label>
                        <select id="location_id" name="location_id" required disabled style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); background: white;">
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" onclick="resetForm()" class="btn btn-secondary">Reset</button>
                    <button type="submit" id="btnSubmit" class="btn btn-primary" disabled>
                        <i class="bi bi-save"></i> Simpan Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let html5QrCode;
function startCamera() {
    html5QrCode = new Html5Qrcode("reader");
    
    // Coba kamera belakang dulu (environment), jika gagal coba kamera apa saja (user/laptop)
    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 250, height: 250 } },
        onScanSuccess
    ).catch(err => {
        console.warn("Kamera belakang tidak ditemukan, mencoba kamera depan/laptop...", err);
        // Fallback ke kamera default (biasanya satu-satunya kamera di laptop)
        html5QrCode.start(
            { facingMode: "user" },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            onScanSuccess
        ).catch(err2 => {
            console.error("Gagal memulai kamera:", err2);
            document.getElementById('scannerStatus').innerHTML = '<span style="color: var(--danger);">Kamera Error/Izin Ditolak</span>';
            Swal.fire({
                icon: 'error',
                title: 'Kamera Tidak Akses',
                text: 'Pastikan Anda memberikan izin kamera dan tidak ada aplikasi lain yang sedang menggunakan kamera.',
            });
        });
    });
}

function onScanSuccess(decodedText) {
    document.getElementById('code').value = decodedText;
    document.getElementById('scanAlert').style.display = 'block';
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
    document.getElementById('scanAlert').style.display = 'none';
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
            Swal.fire({ icon: 'error', title: 'Gagal!', text: result.message || 'Kode QR sudah ada!' });
        }
    } catch (err) {
        Swal.fire('Error', 'Gagal menghubungi server.', 'error');
    } finally {
        btn.disabled = false;
    }
}

function playBeep() {
    const c = new (window.AudioContext || window.webkitAudioContext)();
    const o = c.createOscillator(); const g = c.createGain();
    o.connect(g); g.connect(c.destination); o.frequency.value = 800; o.type = 'sine'; g.gain.value = 0.3;
    o.start(); setTimeout(() => { o.stop(); c.close(); }, 200);
}
</script>

<style>
    #reader video { object-fit: cover; }
    .hidden { display: none; }
</style>
@endsection
