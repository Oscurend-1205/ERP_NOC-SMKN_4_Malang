<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pengajuan Pengadaan - {{ $procurement->code }}</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm 1.8cm 1.5cm 1.8cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            line-height: 1.35;
            background: #fff;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            position: relative;
        }
        .header h3 {
            margin: 0;
            font-size: 13pt;
            font-weight: normal;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 2px 0;
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            font-size: 10pt;
            font-style: italic;
        }
        .title-box {
            text-align: center;
            margin: 15px 0 20px 0;
        }
        .title-box h4 {
            margin: 0;
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .title-box span {
            font-size: 10pt;
            font-family: 'Courier New', Courier, monospace;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 15px;
            font-size: 10.5pt;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 3px 6px;
            vertical-align: top;
        }
        .meta-table td.label {
            width: 28%;
            font-weight: bold;
        }
        .meta-table td.separator {
            width: 2%;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0 20px 0;
            font-size: 10pt;
        }
        .item-table th, .item-table td {
            border: 1px solid #000;
            padding: 6px 8px;
        }
        .item-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9.5pt;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: 'Courier New', Courier, monospace; }
        .justification-box {
            margin: 15px 0;
            font-size: 10.5pt;
            border: 1px dashed #666;
            padding: 8px 12px;
            background: #fafafa;
        }
        .signature-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
            font-size: 10.5pt;
        }
        .signature-table td {
            text-align: center;
            vertical-align: top;
            width: 50%;
            padding: 10px;
        }
        .signature-space {
            height: 65px;
        }
        .sign-name {
            font-weight: bold;
            text-decoration: underline;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                margin: 0;
            }
        }
        .print-toolbar {
            background: #1A1E35;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: sans-serif;
            font-size: 13px;
        }
        .btn-print {
            background: #3F51B5;
            color: #fff;
            border: none;
            padding: 6px 16px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="print-toolbar no-print">
    <div>
        <strong>ERP NOC SMKN 4 Malang</strong> — Cetak Surat Pengajuan Pengadaan ({{ $procurement->code }})
    </div>
    <button onclick="window.print()" class="btn-print">🖨️ Cetak / Simpan PDF</button>
</div>

<div style="padding: 20px 30px;">
    <!-- KOP SURAT RESMI -->
    <div class="header">
        <h3>Pemerintah Provinsi Jawa Timur</h3>
        <h3>Dinas Pendidikan</h3>
        <h2>SMK Negeri 4 Malang</h2>
        <p>Jl. Tanimbar No. 22 Malang, Jawa Timur 65117 | Telp: (0341) 366934 | Website: www.smkn4malang.sch.id</p>
    </div>

    <!-- JUDUL SURAT -->
    <div class="title-box">
        <h4>FORMULIR USULAN PENGAJUAN PENGADAAN ALAT & BAHAN</h4>
        <span>Nomor Registrasi: {{ $procurement->code }}</span>
    </div>

    <!-- METADATA PENGAJUAN -->
    <table class="meta-table">
        <tr>
            <td class="label">Judul Pengadaan</td>
            <td class="separator">:</td>
            <td><strong>{{ $procurement->title }}</strong></td>
        </tr>
        <tr>
            <td class="label">Unit / Program Keahlian</td>
            <td class="separator">:</td>
            <td>{{ $procurement->jurusan->nama_jurusan ?? 'Pusat Network Operation Center (NOC)' }}</td>
        </tr>
        <tr>
            <td class="label">Nama Pengusul</td>
            <td class="separator">:</td>
            <td>{{ $procurement->user->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tingkat Prioritas</td>
            <td class="separator">:</td>
            <td><strong>{{ strtoupper($procurement->priority) }}</strong></td>
        </tr>
        <tr>
            <td class="label">Target Waktu Kebutuhan</td>
            <td class="separator">:</td>
            <td>{{ $procurement->target_date ? $procurement->target_date->translatedFormat('d F Y') : 'Fleksibel' }}</td>
        </tr>
        <tr>
            <td class="label">Status Usulan Saat Ini</td>
            <td class="separator">:</td>
            <td><strong>{{ strtoupper($procurement->status_label) }}</strong></td>
        </tr>
    </table>

    <!-- JUSTIFIKASI / URGENSI -->
    <div class="justification-box">
        <strong>Latar Belakang & Justifikasi Kebutuhan:</strong><br>
        {{ $procurement->justification ?: 'Pengadaan alat dan perangkat untuk mendukung kegiatan operasional, praktikum siswa, dan kehandalan infrastruktur jaringan SMKN 4 Malang.' }}
    </div>

    <!-- TABEL RINCIAN ITEM -->
    <table class="item-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 32%;">Nama Alat / Barang</th>
                <th style="width: 25%;">Spesifikasi / Merk</th>
                <th style="width: 8%;">Qty</th>
                <th style="width: 8%;">Satuan</th>
                <th style="width: 11%;">Harga Satuan</th>
                <th style="width: 11%;">Total Biaya</th>
            </tr>
        </thead>
        <tbody>
            @foreach($procurement->items as $idx => $item)
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td><strong>{{ $item->item_name }}</strong></td>
                <td>{{ $item->specification ?: '-' }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-center">{{ $item->unit }}</td>
                <td class="text-right font-mono">{{ number_format($item->estimated_unit_price, 0, ',', '.') }}</td>
                <td class="text-right font-mono">{{ number_format($item->subtotal_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td colspan="6" class="text-right">TOTAL ESTIMASI ANGGARAN (RP):</td>
                <td class="text-right font-mono">{{ number_format($procurement->total_estimated_cost, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- BLOK TANDA TANGAN -->
    <table class="signature-table">
        <tr>
            <td>
                Malang, {{ $procurement->created_at->translatedFormat('d F Y') }}<br>
                Pengusul / Pemohon,
                <div class="signature-space"></div>
                <div class="sign-name">{{ $procurement->user->name ?? 'Pemohon' }}</div>
                <div>NIP/ID: {{ $procurement->user->user_code ?? '-' }}</div>
            </td>
            <td>
                Menyetujui,<br>
                Kepala Program Keahlian / Jurusan,
                <div class="signature-space"></div>
                <div class="sign-name">{{ $procurement->jurusan->kepala_jurusan ?? '( .............................................. )' }}</div>
                <div>NIP: ..............................................</div>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 25px;">
                Verifikator Sarpras / NOC,<br>
                Kepala Laboratorium NOC,
                <div class="signature-space"></div>
                <div class="sign-name">{{ $procurement->approver->name ?? '( .............................................. )' }}</div>
                <div>Status: {{ $procurement->status === 'approved' ? 'Telah Diverifikasi & Disetujui' : 'Dalam Proses' }}</div>
            </td>
            <td style="padding-top: 25px;">
                Mengetahui,<br>
                Waka Sarana & Prasarana SMKN 4 Malang,
                <div class="signature-space"></div>
                <div class="sign-name">( .............................................. )</div>
                <div>NIP: ..............................................</div>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
