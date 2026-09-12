<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Ringkasan Aktivitas - ERP NOC</title>
    <style>
        @page { size: A4 portrait; margin: 1.5cm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12px; line-height: 1.6; color: #000; padding: 20px; }
        
        /* Letterhead / Kop Surat */
        .kop-surat { border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 25px; text-align: center; position: relative; }
        .kop-surat .logo-container { position: absolute; left: 0; top: 0; width: 80px; height: 80px; }
        .kop-surat .logo-container img { width: 100%; height: 100%; object-fit: contain; }
        .kop-surat .header-text { padding-left: 90px; }
        .kop-surat h1 { font-size: 18px; text-transform: uppercase; margin-bottom: 2px; font-weight: bold; }
        .kop-surat h2 { font-size: 16px; text-transform: uppercase; margin-bottom: 2px; }
        .kop-surat p { font-size: 10px; font-style: italic; margin-bottom: 0; }
        
        .doc-title { text-align: center; margin-bottom: 30px; }
        .doc-title h3 { font-size: 16px; text-decoration: underline; text-transform: uppercase; margin-bottom: 5px; }
        .doc-title p { font-size: 11px; }

        .section-title { font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #000; margin-bottom: 10px; margin-top: 20px; font-size: 13px; }
        
        .grid { display: flex; flex-wrap: wrap; margin-bottom: 20px; }
        .grid-item { width: 50%; padding: 5px 0; }
        .grid-item label { font-weight: bold; width: 150px; display: inline-block; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; margin-top: 10px; }
        th { background: #f2f2f2; padding: 8px 10px; border: 1px solid #000; font-weight: bold; text-align: left; }
        td { padding: 8px 10px; border: 1px solid #000; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .footer-note { margin-top: 30px; font-size: 10px; font-style: italic; }

        .ttd-container { width: 100%; margin-top: 50px; }
        .ttd-table { width: 100%; border: none; }
        .ttd-table td { border: none; padding: 0; width: 50%; text-align: center; }
        .ttd-space { height: 80px; }
        .signature-img { max-height: 60px; max-width: 150px; }

        @media print {
            .no-print { display: none !important; }
        }
        .print-btn { position: fixed; top: 20px; right: 20px; background: #1a56db; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">Cetak Ringkasan (PDF)</button>

    @php
        $settings = \App\Models\ReportSetting::getActive();
    @endphp

    <div class="kop-surat">
        @if($settings->logo_path)
            <div class="logo-container">
                <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Logo Sekolah">
            </div>
        @endif
        <div class="header-text {{ $settings->logo_path ? '' : 'no-logo' }}">
            <h1>{{ $settings->school_name }}</h1>
            <h1>{{ $settings->school_address }}</h1>
            <h2>{{ $settings->school_phone }}</h2>
            <p>{{ $settings->school_email }}</p>
            <p>{{ $settings->school_website }}</p>
        </div>
    </div>

    <div class="doc-title">
        <h3>{{ $settings->report_title }}</h3>
        <p>Periode Laporan: Per {{ now()->translatedFormat('d F Y') }}</p>
    </div>

    <div class="section-title">I. RINGKASAN STATISTIK UTAMA</div>
    <div class="grid">
        <div class="grid-item"><label>Total Aset Barang</label>: {{ number_format($stats['totalAset']) }} Unit</div>
        <div class="grid-item"><label>Total Nilai Aset</label>: Rp {{ number_format($stats['totalNilai'], 0, ',', '.') }}</div>
        <div class="grid-item"><label>Barang Masuk</label>: {{ number_format($stats['barangMasuk']) }} Transaksi</div>
        <div class="grid-item"><label>Barang Keluar/Pinjam</label>: {{ number_format($stats['barangKeluar']) }} Transaksi</div>
        <div class="grid-item"><label>Peminjaman Aktif</label>: {{ number_format($stats['peminjamanAktif']) }} Item</div>
    </div>

    <div class="section-title">II. RINGKASAN KONDISI BARANG</div>
    <table>
        <thead>
            <tr>
                <th>Kondisi Barang</th>
                <th class="text-center">Jumlah Item</th>
                <th class="text-center">Persentase</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Kondisi Baik</td>
                <td class="text-center">{{ $stats['kondisi']['baik'] }}</td>
                <td class="text-center">{{ $stats['totalAset'] > 0 ? round(($stats['kondisi']['baik'] / $stats['totalAset']) * 100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td>Rusak (Ringan/Berat)</td>
                <td class="text-center">{{ $stats['kondisi']['rusak'] }}</td>
                <td class="text-center">{{ $stats['totalAset'] > 0 ? round(($stats['kondisi']['rusak'] / $stats['totalAset']) * 100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td>Hilang / Dimusnahkan</td>
                <td class="text-center">{{ $stats['kondisi']['hilang'] }}</td>
                <td class="text-center">{{ $stats['totalAset'] > 0 ? round(($stats['kondisi']['hilang'] / $stats['totalAset']) * 100, 1) : 0 }}%</td>
            </tr>
        </tbody>
        <tfoot>
            <tr style="font-weight: bold;">
                <td>TOTAL</td>
                <td class="text-center">{{ $stats['totalAset'] }}</td>
                <td class="text-center">100%</td>
            </tr>
        </tfoot>
    </table>

    <div class="section-title">III. DISTRIBUSI KATEGORI ASET</div>
    <table>
        <thead>
            <tr>
                <th>Nama Kategori</th>
                <th class="text-center">Jumlah Unit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stats['categories'] as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td class="text-center">{{ $category->items_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-note">
        * Laporan ini dihasilkan secara otomatis oleh Sistem ERP NOC SMKN 4 Malang pada {{ now()->translatedFormat('d F Y, H:i') }} WIB.
    </div>

    <div class="ttd-container">
        <table class="ttd-table">
            <tr>
                <td>
                    Mengetahui,<br>
                    {{ $settings->head_of_department_position }}<br>
                    <div class="ttd-space"></div>
                    @if($settings->head_of_department_signature)
                        <img src="{{ asset('storage/' . $settings->head_of_department_signature) }}" alt="Tanda Tangan" class="signature-img"><br>
                    @else
                        <strong>__________________________</strong><br>
                    @endif
                    <strong>{{ $settings->head_of_department_name }}</strong><br>
                    NIP. {{ $settings->head_of_department_nip }}
                </td>
                <td>
                    Malang, {{ now()->translatedFormat('d F Y') }}<br>
                    {{ $settings->staff_position }}<br>
                    <div class="ttd-space"></div>
                    @if($settings->staff_signature)
                        <img src="{{ asset('storage/' . $settings->staff_signature) }}" alt="Tanda Tangan" class="signature-img"><br>
                    @else
                        <strong>__________________________</strong><br>
                    @endif
                    <strong>{{ $settings->staff_name }}</strong><br>
                    NIP. {{ $settings->staff_nip }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
