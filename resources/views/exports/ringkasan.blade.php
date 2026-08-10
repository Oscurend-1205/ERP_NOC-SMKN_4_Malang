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
        .kop-surat { border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 25px; text-align: center; }
        .kop-surat h1 { font-size: 18px; text-transform: uppercase; margin-bottom: 2px; font-weight: bold; }
        .kop-surat h2 { font-size: 16px; text-transform: uppercase; margin-bottom: 2px; }
        .kop-surat p { font-size: 10px; font-style: italic; }
        
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

        @media print {
            .no-print { display: none !important; }
        }
        .print-btn { position: fixed; top: 20px; right: 20px; background: #1a56db; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">Cetak Ringkasan (PDF)</button>

    <div class="kop-surat">
        <h1>PEMERINTAH PROVINSI JAWA TIMUR</h1>
        <h1>DINAS PENDIDIKAN</h1>
        <h2>SMK NEGERI 4 MALANG</h2>
        <p>Jl. Tanimbar No. 22 Malang, Telp. (0341) 322515, Fax (0341) 351940</p>
        <p>Website: www.smkn4malang.sch.id | Email: info@smkn4malang.sch.id</p>
    </div>

    <div class="doc-title">
        <h3>LAPORAN RINGKASAN AKTIVITAS INVENTARIS</h3>
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
                    Kepala Lab NOC<br>
                    <div class="ttd-space"></div>
                    <strong>__________________________</strong><br>
                    NIP. ...........................
                </td>
                <td>
                    Malang, {{ now()->translatedFormat('d F Y') }}<br>
                    Petugas Inventaris<br>
                    <div class="ttd-space"></div>
                    <strong>{{ auth()->user()->name }}</strong><br>
                    Jabatan: {{ auth()->user()->role }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
