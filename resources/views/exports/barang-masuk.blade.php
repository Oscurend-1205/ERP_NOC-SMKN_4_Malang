<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang Masuk - ERP NOC</title>
    <style>
        @page { size: A4 landscape; margin: 1.5cm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 11px; line-height: 1.4; color: #000; padding: 20px; }
        
        /* Letterhead / Kop Surat */
        .kop-surat { border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 20px; text-align: center; }
        .kop-surat h1 { font-size: 18px; text-transform: uppercase; margin-bottom: 2px; font-weight: bold; }
        .kop-surat h2 { font-size: 16px; text-transform: uppercase; margin-bottom: 2px; }
        .kop-surat p { font-size: 10px; font-style: italic; }
        
        .doc-title { text-align: center; margin-bottom: 20px; }
        .doc-title h3 { font-size: 14px; text-decoration: underline; text-transform: uppercase; margin-bottom: 5px; }
        .doc-title p { font-size: 10px; }

        .meta-info { margin-bottom: 15px; width: 100%; }
        .meta-info td { padding: 2px 0; font-size: 10px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #f2f2f2; padding: 8px 5px; border: 1px solid #000; font-weight: bold; text-transform: uppercase; font-size: 9px; }
        td { padding: 6px 5px; border: 1px solid #000; vertical-align: top; }
        .text-center { text-align: center; }
        
        .badge { font-weight: bold; text-transform: uppercase; font-size: 8px; }
        
        .ttd-container { width: 100%; margin-top: 30px; }
        .ttd-table { width: 100%; border: none; }
        .ttd-table td { border: none; padding: 0; width: 50%; text-align: center; }
        .ttd-space { height: 70px; }

        @media print {
            .no-print { display: none !important; }
        }
        .print-btn { position: fixed; top: 20px; right: 20px; background: #1a56db; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">Cetak Laporan (PDF)</button>

    <div class="kop-surat">
        <h1>PEMERINTAH PROVINSI JAWA TIMUR</h1>
        <h1>DINAS PENDIDIKAN</h1>
        <h2>SMK NEGERI 4 MALANG</h2>
        <p>Jl. Tanimbar No. 22 Malang, Telp. (0341) 322515, Fax (0341) 351940</p>
        <p>Website: www.smkn4malang.sch.id | Email: info@smkn4malang.sch.id</p>
    </div>

    <div class="doc-title">
        <h3>LAPORAN BARANG MASUK</h3>
        <p>Nomor: MASUK/NOC/{{ now()->format('Y/m/d') }}/{{ rand(100,999) }}</p>
    </div>

    <table class="meta-info">
        <tr>
            <td style="width: 120px;">Unit Kerja</td>
            <td style="width: 10px;">:</td>
            <td>Laboratorium Network Operation Center (NOC)</td>
            <td style="text-align: right;">Tanggal Cetak: {{ now()->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Total Catatan</td>
            <td>:</td>
            <td>{{ $movements->count() }} Transaksi</td>
            <td></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 80px;">Tanggal</th>
                <th style="width: 100px;">Kode Barang</th>
                <th>Nama Barang</th>
                <th style="width: 80px;">Kategori</th>
                <th style="width: 70px;">Kondisi</th>
                <th style="width: 50px;">Jumlah</th>
                <th style="width: 100px;">Lokasi Tujuan</th>
                <th style="width: 90px;">Dicatat Oleh</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movements as $i => $m)
                @php $item = $m->item; @endphp
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td class="text-center">{{ $m->movement_date ? $m->movement_date->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">{{ $item->code ?? '-' }}</td>
                    <td>{{ $item->name ?? '-' }}</td>
                    <td class="text-center">{{ $item->category->name ?? '-' }}</td>
                    <td class="text-center"><span class="badge">{{ $item->condition_label ?? '-' }}</span></td>
                    <td class="text-center">{{ $m->quantity }}</td>
                    <td class="text-center">{{ $m->toLocation->name ?? '-' }}</td>
                    <td class="text-center">{{ $m->user->name ?? '-' }}</td>
                    <td>{{ $m->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="10" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

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
                    <strong>__________________________</strong><br>
                    NIP. ...........................
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
