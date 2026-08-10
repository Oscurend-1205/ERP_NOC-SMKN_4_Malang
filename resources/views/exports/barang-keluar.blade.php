<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang Keluar - ERP NOC</title>
    <style>
        @page { size: A4 landscape; margin: 1.5cm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 11px; line-height: 1.4; color: #000; padding: 20px; }
        
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
        <h3>LAPORAN BARANG KELUAR / PEMINJAMAN</h3>
        <p>Nomor: KELUAR/NOC/{{ now()->format('Y/m/d') }}/{{ rand(100,999) }}</p>
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
            <td>{{ $peminjamans->count() }} Transaksi</td>
            <td></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 70px;">ID Pinjam</th>
                <th>Nama Peminjam</th>
                <th style="width: 90px;">Kelas/Jurusan</th>
                <th style="width: 90px;">Kode Barang</th>
                <th>Nama Barang</th>
                <th style="width: 70px;">Tgl Pinjam</th>
                <th style="width: 70px;">Tgl Kembali</th>
                <th style="width: 70px;">Status</th>
                <th style="width: 80px;">Kondisi Kembali</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamans as $i => $p)
                @php $item = $p->item; @endphp
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td class="text-center">PJ-{{ str_pad($p->id_pinjam, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $p->nama_peminjam }}</td>
                    <td class="text-center">{{ $p->kelas ?? '-' }}</td>
                    <td class="text-center">{{ $p->item_code }}</td>
                    <td>{{ $item->name ?? '-' }}</td>
                    <td class="text-center">{{ $p->waktu_pinjam ? $p->waktu_pinjam->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">{{ $p->waktu_kembali ? $p->waktu_kembali->format('d/m/Y') : '-' }}</td>
                    <td class="text-center"><span class="badge">{{ $p->status_label }}</span></td>
                    <td class="text-center">
                        @if($p->kondisi_saat_kembali)
                            <span class="badge">{{ ucfirst(str_replace('_',' ',$p->kondisi_saat_kembali)) }}</span>
                        @else - @endif
                    </td>
                    <td>{{ $p->catatan ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="11" class="text-center">Tidak ada data.</td></tr>
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
