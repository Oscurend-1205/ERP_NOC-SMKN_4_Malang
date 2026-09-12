<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang Keluar - ERP NOC</title>
    <style>
        @page { size: A4 landscape; margin: 1.5cm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 11px; line-height: 1.4; color: #000; padding: 20px; }
        
        .kop-surat { border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 20px; position: relative; }
        .kop-surat .logo-container { position: absolute; left: 0; top: 0; width: 80px; height: 80px; }
        .kop-surat .logo-container img { width: 100%; height: 100%; object-fit: contain; }
        .kop-surat .header-text { padding-left: 90px; }
        .kop-surat .header-text.no-logo { padding-left: 0; text-align: center; }
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
        
        .badge { font-weight: bold; text-transform: uppercase; font-size: 8px; }
        
        .ttd-container { width: 100%; margin-top: 30px; }
        .ttd-table { width: 100%; border: none; }
        .ttd-table td { border: none; padding: 0; width: 50%; text-align: center; }
        .ttd-space { height: 70px; }
        .signature-img { max-height: 60px; max-width: 150px; }

        @media print {
            .no-print { display: none !important; }
        }
        .print-btn { position: fixed; top: 20px; right: 20px; background: #1a56db; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">Cetak Laporan (PDF)</button>

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
        <h3>LAPORAN BARANG KELUAR / PEMINJAMAN</h3>
        <p>Nomor: KELUAR/NOC/{{ now()->format('Y/m/d') }}/{{ rand(100,999) }}</p>
    </div>

    <table class="meta-info">
        <tr>
            <td style="width: 120px;">Unit Kerja</td>
            <td style="width: 10px;">:</td>
            <td>{{ $settings->department_name }}</td>
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
