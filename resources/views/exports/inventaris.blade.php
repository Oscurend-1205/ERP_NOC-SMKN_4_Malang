<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Daftar Inventaris - ERP NOC</title>
    <style>
        @page { size: A4 landscape; margin: 1.5cm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 11px; line-height: 1.4; color: #000; padding: 20px; }
        
        /* Letterhead / Kop Surat */
        .kop-surat { border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 20px; position: relative; }
        .kop-surat .logo-placeholder { position: absolute; left: 0; top: 0; width: 80px; height: 80px; border: 1px solid #ddd; display: flex; items-center; justify-center; font-size: 8px; text-align: center; }
        .kop-surat .header-text { text-align: center; }
        .kop-surat h1 { font-size: 18px; text-transform: uppercase; margin-bottom: 2px; font-weight: bold; }
        .kop-surat h2 { font-size: 16px; text-transform: uppercase; margin-bottom: 2px; }
        .kop-surat p { font-size: 10px; font-style: italic; margin-bottom: 0; }
        
        .doc-title { text-align: center; margin-bottom: 20px; }
        .doc-title h3 { font-size: 14px; text-decoration: underline; text-transform: uppercase; margin-bottom: 5px; }
        .doc-title p { font-size: 10px; }

        .meta-info { margin-bottom: 15px; width: 100%; }
        .meta-info td { padding: 2px 0; font-size: 10px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #f2f2f2; padding: 8px 5px; border: 1px solid #000; font-weight: bold; text-transform: uppercase; font-size: 9px; }
        td { padding: 6px 5px; border: 1px solid #000; vertical-align: top; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .badge { font-weight: bold; text-transform: uppercase; font-size: 8px; }
        
        .ttd-container { width: 100%; margin-top: 30px; }
        .ttd-table { width: 100%; border: none; }
        .ttd-table td { border: none; padding: 0; width: 33%; text-align: center; }
        .ttd-space { height: 70px; }

        .no-print { display: none; }
        @media print {
            .no-print { display: none !important; }
            .print-btn { display: none !important; }
        }
        .print-btn { position: fixed; top: 20px; right: 20px; background: #1a56db; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">Cetak Laporan (PDF)</button>

    <div class="kop-surat">
        <div class="header-text">
            <h1>PEMERINTAH PROVINSI JAWA TIMUR</h1>
            <h1>DINAS PENDIDIKAN</h1>
            <h2>SMK NEGERI 4 MALANG</h2>
            <p>Jl. Tanimbar No. 22 Malang, Telp. (0341) 322515, Fax (0341) 351940</p>
            <p>Website: www.smkn4malang.sch.id | Email: info@smkn4malang.sch.id</p>
        </div>
    </div>

    <div class="doc-title">
        <h3>LAPORAN DAFTAR INVENTARIS BARANG</h3>
        <p>Nomor: INV/NOC/{{ now()->format('Y/m/d') }}/{{ rand(100,999) }}</p>
    </div>

    <table class="meta-info">
        <tr>
            <td style="width: 120px;">Unit Kerja</td>
            <td style="width: 10px;">:</td>
            <td>Laboratorium Network Operation Center (NOC)</td>
            <td style="text-align: right;">Tanggal Cetak: {{ now()->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Total Inventaris</td>
            <td>:</td>
            <td>{{ $items->count() }} Item</td>
            <td></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 100px;">Kode Barang</th>
                <th>Nama Barang</th>
                <th style="width: 80px;">Kategori</th>
                <th style="width: 80px;">Lokasi</th>
                <th style="width: 70px;">Kondisi</th>
                <th style="width: 70px;">Status</th>
                <th style="width: 80px;">Tgl Beli</th>
                <th style="width: 90px;">Harga Satuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $item->code }}</td>
                <td>
                    <strong>{{ $item->name }}</strong><br>
                    <small>{{ $item->brand }} {{ $item->model }}</small>
                </td>
                <td class="text-center">{{ $item->category->name ?? '-' }}</td>
                <td class="text-center">{{ $item->location->name ?? '-' }}</td>
                <td class="text-center">
                    <span class="badge">{{ $item->condition_label }}</span>
                </td>
                <td class="text-center">
                    <span class="badge">{{ $item->status_label }}</span>
                </td>
                <td class="text-center">{{ $item->purchase_date ? $item->purchase_date->format('d/m/Y') : '-' }}</td>
                <td class="text-right">Rp {{ number_format($item->purchase_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="8" class="text-right">Total Nilai Aset</th>
                <th class="text-right">Rp {{ number_format($items->sum('purchase_price'), 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="ttd-container">
        <table class="ttd-table">
            <tr>
                <td>
                    Menyetujui,<br>
                    Kepala Sekolah<br>
                    <div class="ttd-space"></div>
                    <strong>__________________________</strong><br>
                    NIP. ...........................
                </td>
                <td></td>
                <td>
                    Malang, {{ now()->translatedFormat('d F Y') }}<br>
                    Kepala Lab NOC<br>
                    <div class="ttd-space"></div>
                    <strong>__________________________</strong><br>
                    NIP. ...........................
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
