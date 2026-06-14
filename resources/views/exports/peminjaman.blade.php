<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman - ERP NOC</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 11px; color: #1a1a1a; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px double #333; padding-bottom: 15px; }
        .header h1 { font-size: 18px; font-weight: 700; color: #1a1a1a; margin-bottom: 4px; }
        .header h2 { font-size: 14px; font-weight: 600; color: #333; margin-bottom: 2px; }
        .header p { font-size: 10px; color: #666; }
        .meta { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 10px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th { background: #2c3e50; color: #fff; padding: 6px 8px; text-align: left; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #1a252f; }
        td { padding: 5px 8px; border: 1px solid #ddd; font-size: 10px; vertical-align: top; }
        tr:nth-child(even) { background: #f8f9fa; }
        .text-center { text-align: center; }
        .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #666; }
        .footer .signature { margin-top: 40px; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 9px; font-weight: 600; }
        .badge-dipinjam { background: #fef3c7; color: #92400e; }
        .badge-dikembalikan { background: #d1fae5; color: #065f46; }
        .badge-baik { background: #d1fae5; color: #065f46; }
        .badge-rusak_ringan { background: #fef3c7; color: #92400e; }
        .badge-rusak_berat { background: #fee2e2; color: #991b1b; }
        .badge-hilang { background: #e5e7eb; color: #374151; }
        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
            @page { size: A4 landscape; margin: 10mm; }
        }
        .print-btn { position: fixed; top: 20px; right: 20px; background: #3F51B5; color: #fff; border: none; padding: 10px 24px; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600; box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
        .print-btn:hover { background: #303F9F; }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>

    <div class="header">
        <h1>ERP NOC - SMKN 4 Malang</h1>
        <h2>Laporan Data Peminjaman</h2>
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>

    <div class="meta">
        <span>Total data: <strong>{{ $peminjamans->count() }}</strong> catatan</span>
        <span>Periode: {{ now()->format('d-m-Y') }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width:30px">No</th>
                <th>ID Pinjam</th>
                <th>Nama Peminjam</th>
                <th>Jurusan</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Kondisi Kembali</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamans as $i => $p)
                @php $item = $p->item; @endphp
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>PJ-{{ str_pad($p->id_pinjam, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $p->nama_peminjam }}</td>
                    <td>{{ $p->kelas ?? '-' }}</td>
                    <td>{{ $p->item_code }}</td>
                    <td>{{ $item->name ?? '-' }}</td>
                    <td>{{ $item->category->name ?? '-' }}</td>
                    <td>{{ $p->waktu_pinjam ? $p->waktu_pinjam->format('d-m-Y') : '-' }}</td>
                    <td>{{ $p->waktu_kembali ? $p->waktu_kembali->format('d-m-Y') : '-' }}</td>
                    <td><span class="badge badge-{{ $p->status }}">{{ $p->status_label }}</span></td>
                    <td>
                        @if($p->kondisi_saat_kembali)
                            <span class="badge badge-{{ $p->kondisi_saat_kembali }}">{{ ucfirst(str_replace('_',' ',$p->kondisi_saat_kembali)) }}</span>
                        @else - @endif
                    </td>
                    <td>{{ $p->catatan ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="12" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <p>Mengetahui,</p>
            <p style="margin-top: 50px;"><strong>__________________________</strong></p>
            <p>Kepala Lab NOC</p>
        </div>
    </div>
</body>
</html>
