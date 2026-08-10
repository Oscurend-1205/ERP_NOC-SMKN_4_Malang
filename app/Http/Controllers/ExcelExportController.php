<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Location;
use App\Models\Peminjaman;
use App\Models\ItemMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\Legend;

class ExcelExportController extends Controller
{
    /**
     * Export Laporan Lengkap — Multi-sheet Excel (.xlsx)
     * Sheet 1: Ringkasan (Dashboard Summary)
     * Sheet 2: Inventaris (All Items by Category)
     * Sheet 3: Peminjaman (Borrowing Records)
     * Sheet 4: Barang Masuk (Incoming Movements)
     * Sheet 5: Barang Keluar (Outgoing/Borrowing)
     */
    public function laporanLengkap(Request $request)
    {
        $spreadsheet = new Spreadsheet();

        // ─── Color Palette ──────────────────────────────
        $INDIGO   = 'FF3F51B5';
        $INDIGO_L = 'FFE8EAF6';
        $DARK     = 'FF1F2937';
        $WHITE    = 'FFFFFFFF';
        $GREEN    = 'FF16A34A';
        $GREEN_L  = 'FFDCFCE7';
        $RED      = 'FFDC2626';
        $RED_L    = 'FFFEE2E2';
        $AMBER    = 'FFD97706';
        $AMBER_L  = 'FFFEF3C7';
        $BLUE     = 'FF2563EB';
        $BLUE_L   = 'FFDBEAFE';
        $GRAY     = 'FF6B7280';
        $GRAY_L   = 'FFF3F4F6';
        $BORDER_C = 'FFD1D5DB';

        // ═══════════════════════════════════════════════════
        // SHEET 1: RINGKASAN (Summary Dashboard)
        // ═══════════════════════════════════════════════════
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Ringkasan');
        $sheet->setShowGridlines(false);

        // --- Title Block ---
        $sheet->mergeCells('B2:G2');
        $sheet->setCellValue('B2', 'LAPORAN LENGKAP INVENTARIS');
        $sheet->getStyle('B2')->getFont()->setSize(20)->setBold(true)->setColor(new Color($INDIGO));
        $sheet->getStyle('B2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $sheet->mergeCells('B3:G3');
        $sheet->setCellValue('B3', 'ERP NOC — SMKN 4 Malang');
        $sheet->getStyle('B3')->getFont()->setSize(12)->setColor(new Color($GRAY));

        $sheet->mergeCells('B4:G4');
        $sheet->setCellValue('B4', 'Dicetak: ' . now()->translatedFormat('d F Y, H:i') . ' WIB');
        $sheet->getStyle('B4')->getFont()->setSize(10)->setItalic(true)->setColor(new Color($GRAY));

        // --- Stats Cards (Row 6-9) ---
        $totalItems     = Item::count();
        $totalBaik      = Item::where('condition', 'baik')->count();
        $totalRusak     = Item::where('condition', 'rusak_ringan')->count() + Item::where('condition', 'rusak_berat')->count();
        $totalMaintenance = Item::where('status', 'maintenance')->count();
        $totalTersedia  = Item::where('status', 'tersedia')->count();
        $totalDipinjam  = Item::where('status', 'dipinjam')->count();
        $totalValue     = Item::sum('purchase_price');
        $totalCategories = Category::count();
        $totalLocations  = Location::count();
        $totalPeminjaman = Peminjaman::count();
        $peminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $peminjamanSelesai = Peminjaman::where('status', 'dikembalikan')->count();

        $stats = [
            ['B', 'Total Aset', $totalItems . ' unit', $INDIGO, $INDIGO_L],
            ['D', 'Nilai Aset', 'Rp ' . number_format($totalValue, 0, ',', '.'), $GREEN, $GREEN_L],
            ['F', 'Peminjaman Aktif', $peminjamanAktif . ' unit', $AMBER, $AMBER_L],
        ];

        foreach ($stats as $s) {
            [$col, $label, $value, $color, $bgColor] = $s;
            $row1 = 6; $row2 = 8;
            // Label
            $sheet->mergeCells("{$col}{$row1}:" . $this->nextCol($col) . "{$row1}");
            $sheet->setCellValue("{$col}{$row1}", $label);
            $sheet->getStyle("{$col}{$row1}")->getFont()->setSize(10)->setBold(true)->setColor(new Color($GRAY));
            // Value box
            $nextC = $this->nextCol($col);
            $sheet->mergeCells("{$col}{$row2}:{$nextC}{$row2}");
            $sheet->setCellValue("{$col}{$row2}", $value);
            $sheet->getStyle("{$col}{$row2}")->getFont()->setSize(16)->setBold(true)->setColor(new Color($color));
            $sheet->getStyle("{$col}{$row2}:{$nextC}{$row2}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($bgColor);
            $sheet->getStyle("{$col}{$row2}:{$nextC}{$row2}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color($BORDER_C));
            $sheet->getStyle("{$col}{$row2}:{$nextC}{$row2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            // Apply fill to merged range
            for ($c = $col; $c <= $nextC; $c++) {
                $sheet->getStyle("{$c}{$row1}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($bgColor);
                $sheet->getStyle("{$c}{$row1}:{$c}" . ($row2 - 1))->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($bgColor);
            }
        }

        // --- Detail Section: Kondisi Breakdown (Row 10+) ---
        $r = 10;
        $sheet->mergeCells("B{$r}:C{$r}");
        $sheet->setCellValue("B{$r}", 'Ringkasan Kondisi Aset');
        $sheet->getStyle("B{$r}")->getFont()->setSize(12)->setBold(true)->setColor(new Color($DARK));
        $r++;

        $kondisiData = [
            ['Baik', $totalBaik, $GREEN, $GREEN_L],
            ['Rusak', $totalRusak, $RED, $RED_L],
            ['Maintenance', $totalMaintenance, $AMBER, $AMBER_L],
            ['Tersedia', $totalTersedia, $BLUE, $BLUE_L],
            ['Dipinjam', $totalDipinjam, $AMBER, $AMBER_L],
        ];

        // Table header
        $this->writeHeaderRow($sheet, $r, ['B', 'C', 'D'], ['Kondisi / Status', 'Jumlah', 'Persentase'], $INDIGO);
        $r++;

        foreach ($kondisiData as $kd) {
            [$label, $count, $color, $bg] = $kd;
            $pct = $totalItems > 0 ? round(($count / $totalItems) * 100, 1) . '%' : '0%';
            $sheet->setCellValue("B{$r}", $label);
            $sheet->setCellValue("C{$r}", $count);
            $sheet->setCellValue("D{$r}", $pct);
            $sheet->getStyle("B{$r}:D{$r}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color($BORDER_C));
            $sheet->getStyle("B{$r}")->getFont()->setBold(true)->setColor(new Color($color));
            $sheet->getStyle("C{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$r}:D{$r}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($bg);
            $r++;
        }

        // --- Category Breakdown (Column F-H) ---
        $catR = 10;
        $sheet->mergeCells("F{$catR}:H{$catR}");
        $sheet->setCellValue("F{$catR}", 'Aset per Kategori');
        $sheet->getStyle("F{$catR}")->getFont()->setSize(12)->setBold(true)->setColor(new Color($DARK));
        $catR++;

        $this->writeHeaderRow($sheet, $catR, ['F', 'G', 'H'], ['Kategori', 'Jumlah Unit', 'Total Nilai'], $INDIGO);
        $catR++;

        $categories = Category::withCount('items')->withSum('items', 'purchase_price')->orderBy('items_count', 'desc')->get();
        foreach ($categories as $cat) {
            $sheet->setCellValue("F{$catR}", $cat->name);
            $sheet->setCellValue("G{$catR}", $cat->items_count);
            $sheet->setCellValue("H{$catR}", $cat->items_sum_purchase_price ?? 0);
            $sheet->getStyle("F{$catR}:H{$catR}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color($BORDER_C));
            $sheet->getStyle("G{$catR}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("H{$catR}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("H{$catR}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $catR++;
        }
        // Total row
        $sheet->setCellValue("F{$catR}", 'TOTAL');
        $sheet->setCellValue("G{$catR}", $totalItems);
        $sheet->setCellValue("H{$catR}", $totalValue);
        $sheet->getStyle("F{$catR}:H{$catR}")->getFont()->setBold(true)->setColor(new Color($WHITE));
        $sheet->getStyle("F{$catR}:H{$catR}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($INDIGO);
        $sheet->getStyle("F{$catR}:H{$catR}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color($INDIGO));
        $sheet->getStyle("H{$catR}")->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("H{$catR}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // --- Info Block (Row after kondisi) ---
        $infoR = $r + 1;
        $sheet->mergeCells("B{$infoR}:D{$infoR}");
        $sheet->setCellValue("B{$infoR}", 'Informasi Umum');
        $sheet->getStyle("B{$infoR}")->getFont()->setSize(12)->setBold(true)->setColor(new Color($DARK));
        $infoR++;
        $infoItems = [
            ['Total Kategori', $totalCategories],
            ['Total Lokasi', $totalLocations],
            ['Total Peminjaman', $totalPeminjaman],
            ['Peminjaman Selesai', $peminjamanSelesai],
        ];
        foreach ($infoItems as $info) {
            $sheet->setCellValue("B{$infoR}", $info[0]);
            $sheet->setCellValue("C{$infoR}", $info[1]);
            $sheet->getStyle("B{$infoR}")->getFont()->setColor(new Color($GRAY));
            $sheet->getStyle("C{$infoR}")->getFont()->setBold(true);
            $sheet->getStyle("B{$infoR}:C{$infoR}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color($BORDER_C));
            $infoR++;
        }

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(3);
        $sheet->getColumnDimension('B')->setWidth(22);
        $sheet->getColumnDimension('C')->setWidth(18);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(3);
        $sheet->getColumnDimension('F')->setWidth(24);
        $sheet->getColumnDimension('G')->setWidth(16);
        $sheet->getColumnDimension('H')->setWidth(20);

        // Row heights
        $sheet->getRowDimension(2)->setRowHeight(30);
        $sheet->getRowDimension(7)->setRowHeight(20);
        $sheet->getRowDimension(8)->setRowHeight(35);

        // ═══════════════════════════════════════════════════
        // SHEET 2: INVENTARIS (All Items)
        // ═══════════════════════════════════════════════════
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Inventaris');

        $items = Item::with(['category', 'location', 'supplier', 'asalBarang'])
            ->orderBy('category_id')->orderBy('code')->get();

        // Title
        $sheet2->mergeCells('A1:L1');
        $sheet2->setCellValue('A1', 'DAFTAR INVENTARIS BARANG — ERP NOC SMKN 4 MALANG');
        $sheet2->getStyle('A1')->getFont()->setSize(14)->setBold(true)->setColor(new Color($INDIGO));
        $sheet2->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet2->mergeCells('A2:L2');
        $sheet2->setCellValue('A2', 'Total: ' . $items->count() . ' unit  |  Dicetak: ' . now()->translatedFormat('d M Y H:i') . ' WIB');
        $sheet2->getStyle('A2')->getFont()->setSize(9)->setItalic(true)->setColor(new Color($GRAY));

        // Headers
        $headers2 = ['No', 'Kode', 'Nama Barang', 'Merek', 'Model', 'Sub Prefix', 'Kategori', 'Lokasi', 'Kondisi', 'Status', 'Tgl Beli', 'Harga Beli'];
        $cols2 = ['A','B','C','D','E','F','G','H','I','J','K','L'];
        $this->writeHeaderRow($sheet2, 4, $cols2, $headers2, $INDIGO);

        // Data rows
        $row = 5;
        foreach ($items as $i => $item) {
            $sheet2->setCellValue("A{$row}", $i + 1);
            $sheet2->setCellValue("B{$row}", $item->code);
            $sheet2->setCellValue("C{$row}", $item->name);
            $sheet2->setCellValue("D{$row}", $item->brand ?? '-');
            $sheet2->setCellValue("E{$row}", $item->model ?? '-');
            $sheet2->setCellValue("F{$row}", $item->sub_prefix ?? '-');
            $sheet2->setCellValue("G{$row}", $item->category->name ?? '-');
            $sheet2->setCellValue("H{$row}", $item->location->name ?? '-');
            $sheet2->setCellValue("I{$row}", $item->condition_label);
            $sheet2->setCellValue("J{$row}", $item->status_label);
            $sheet2->setCellValue("K{$row}", $item->purchase_date ? $item->purchase_date->format('d-m-Y') : '-');
            $sheet2->setCellValue("L{$row}", $item->purchase_price ?? 0);

            // Alternate row color
            if ($i % 2 === 1) {
                $sheet2->getStyle("A{$row}:L{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($GRAY_L);
            }

            // Conditional coloring for Kondisi
            $kondisiColor = match($item->condition) {
                'baik' => $GREEN, 'rusak_ringan' => $AMBER, 'rusak_berat' => $RED, 'hilang' => $GRAY, default => $GRAY
            };
            $sheet2->getStyle("I{$row}")->getFont()->setColor(new Color($kondisiColor))->setBold(true);

            // Conditional coloring for Status
            $statusColor = match($item->status) {
                'tersedia' => $GREEN, 'dipinjam' => $AMBER, 'maintenance' => $BLUE, 'dimusnahkan' => $RED, default => $GRAY
            };
            $sheet2->getStyle("J{$row}")->getFont()->setColor(new Color($statusColor))->setBold(true);

            // Currency format
            $sheet2->getStyle("L{$row}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet2->getStyle("L{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            // Borders
            $sheet2->getStyle("A{$row}:L{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color($BORDER_C));

            $row++;
        }

        // Auto-filter & freeze
        $sheet2->setAutoFilter("A4:L" . ($row - 1));
        $sheet2->freezePane('A5');

        // Column widths
        $widths2 = [5, 16, 25, 14, 16, 10, 18, 20, 14, 14, 12, 16];
        foreach ($cols2 as $i => $col) {
            $sheet2->getColumnDimension($col)->setWidth($widths2[$i]);
        }

        // ═══════════════════════════════════════════════════
        // SHEET 3: PEMINJAMAN
        // ═══════════════════════════════════════════════════
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Peminjaman');

        $peminjamans = Peminjaman::with('item.category')->orderBy('waktu_pinjam', 'desc')->get();

        $sheet3->mergeCells('A1:M1');
        $sheet3->setCellValue('A1', 'LAPORAN DATA PEMINJAMAN — ERP NOC SMKN 4 MALANG');
        $sheet3->getStyle('A1')->getFont()->setSize(14)->setBold(true)->setColor(new Color($INDIGO));
        $sheet3->mergeCells('A2:M2');
        $sheet3->setCellValue('A2', 'Total: ' . $peminjamans->count() . ' catatan  |  Aktif: ' . $peminjamanAktif . '  |  Selesai: ' . $peminjamanSelesai);
        $sheet3->getStyle('A2')->getFont()->setSize(9)->setItalic(true)->setColor(new Color($GRAY));

        $headers3 = ['No', 'ID Pinjam', 'Peminjam', 'Kelas', 'Kode Barang', 'Nama Barang', 'Kategori', 'Tgl Pinjam', 'Tgl Kembali', 'Status', 'Kondisi Kembali', 'Keterangan', 'Catatan'];
        $cols3 = ['A','B','C','D','E','F','G','H','I','J','K','L','M'];
        $this->writeHeaderRow($sheet3, 4, $cols3, $headers3, $INDIGO);

        $row = 5;
        foreach ($peminjamans as $i => $p) {
            $item = $p->item;
            $sheet3->setCellValue("A{$row}", $i + 1);
            $sheet3->setCellValue("B{$row}", 'PJ-' . str_pad($p->id_pinjam, 4, '0', STR_PAD_LEFT));
            $sheet3->setCellValue("C{$row}", $p->nama_peminjam);
            $sheet3->setCellValue("D{$row}", $p->kelas ?? '-');
            $sheet3->setCellValue("E{$row}", $p->item_code);
            $sheet3->setCellValue("F{$row}", $item->name ?? '-');
            $sheet3->setCellValue("G{$row}", $item->category->name ?? '-');
            $sheet3->setCellValue("H{$row}", $p->waktu_pinjam ? $p->waktu_pinjam->format('d-m-Y') : '-');
            $sheet3->setCellValue("I{$row}", $p->waktu_kembali ? $p->waktu_kembali->format('d-m-Y') : '-');
            $sheet3->setCellValue("J{$row}", $p->status_label);
            $sheet3->setCellValue("K{$row}", $p->kondisi_saat_kembali ? ucfirst(str_replace('_', ' ', $p->kondisi_saat_kembali)) : '-');
            $sheet3->setCellValue("L{$row}", $p->keterangan_kembali ?? '-');
            $sheet3->setCellValue("M{$row}", $p->catatan ?? '-');

            if ($i % 2 === 1) {
                $sheet3->getStyle("A{$row}:M{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($GRAY_L);
            }

            // Status coloring
            $stColor = $p->status === 'dipinjam' ? $AMBER : $GREEN;
            $sheet3->getStyle("J{$row}")->getFont()->setColor(new Color($stColor))->setBold(true);

            // Kondisi kembali coloring
            if ($p->kondisi_saat_kembali) {
                $kColor = match($p->kondisi_saat_kembali) {
                    'baik' => $GREEN, 'rusak_ringan' => $AMBER, 'rusak_berat' => $RED, 'hilang' => $GRAY, default => $GRAY
                };
                $sheet3->getStyle("K{$row}")->getFont()->setColor(new Color($kColor))->setBold(true);
            }

            $sheet3->getStyle("A{$row}:M{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color($BORDER_C));
            $row++;
        }

        $sheet3->setAutoFilter("A4:M" . ($row - 1));
        $sheet3->freezePane('A5');

        $widths3 = [5, 12, 20, 12, 16, 22, 16, 12, 12, 14, 16, 28, 22];
        foreach ($cols3 as $i => $col) {
            $sheet3->getColumnDimension($col)->setWidth($widths3[$i]);
        }

        // ═══════════════════════════════════════════════════
        // SHEET 4: BARANG MASUK
        // ═══════════════════════════════════════════════════
        $sheet4 = $spreadsheet->createSheet();
        $sheet4->setTitle('Barang Masuk');

        $movementsIn = ItemMovement::with(['item.category', 'user', 'toLocation'])
            ->where('type', 'masuk')
            ->orderBy('movement_date', 'desc')->get();

        $sheet4->mergeCells('A1:K1');
        $sheet4->setCellValue('A1', 'LAPORAN BARANG MASUK — ERP NOC SMKN 4 MALANG');
        $sheet4->getStyle('A1')->getFont()->setSize(14)->setBold(true)->setColor(new Color($GREEN));
        $sheet4->mergeCells('A2:K2');
        $sheet4->setCellValue('A2', 'Total: ' . $movementsIn->count() . ' catatan  |  Dicetak: ' . now()->translatedFormat('d M Y H:i') . ' WIB');
        $sheet4->getStyle('A2')->getFont()->setSize(9)->setItalic(true)->setColor(new Color($GRAY));

        $headers4 = ['No', 'Tanggal', 'Kode Barang', 'Nama Barang', 'Kategori', 'Merek', 'Kondisi', 'Jumlah', 'Lokasi Tujuan', 'Dicatat Oleh', 'Catatan'];
        $cols4 = ['A','B','C','D','E','F','G','H','I','J','K'];
        $this->writeHeaderRow($sheet4, 4, $cols4, $headers4, $GREEN);

        $row = 5;
        foreach ($movementsIn as $i => $m) {
            $item = $m->item;
            $sheet4->setCellValue("A{$row}", $i + 1);
            $sheet4->setCellValue("B{$row}", $m->movement_date ? $m->movement_date->format('d-m-Y') : '-');
            $sheet4->setCellValue("C{$row}", $item->code ?? '-');
            $sheet4->setCellValue("D{$row}", $item->name ?? '-');
            $sheet4->setCellValue("E{$row}", $item->category->name ?? '-');
            $sheet4->setCellValue("F{$row}", $item->brand ?? '-');
            $sheet4->setCellValue("G{$row}", $item->condition_label ?? '-');
            $sheet4->setCellValue("H{$row}", $m->quantity);
            $sheet4->setCellValue("I{$row}", $m->toLocation->name ?? '-');
            $sheet4->setCellValue("J{$row}", $m->user->name ?? '-');
            $sheet4->setCellValue("K{$row}", $m->notes ?? '-');

            if ($i % 2 === 1) {
                $sheet4->getStyle("A{$row}:K{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($GREEN_L);
            }
            $sheet4->getStyle("A{$row}:K{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color($BORDER_C));
            $sheet4->getStyle("H{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $row++;
        }

        $sheet4->setAutoFilter("A4:K" . ($row - 1));
        $sheet4->freezePane('A5');

        $widths4 = [5, 12, 16, 22, 16, 14, 14, 8, 20, 18, 25];
        foreach ($cols4 as $i => $col) {
            $sheet4->getColumnDimension($col)->setWidth($widths4[$i]);
        }

        // ═══════════════════════════════════════════════════
        // SHEET 5: BARANG KELUAR
        // ═══════════════════════════════════════════════════
        $sheet5 = $spreadsheet->createSheet();
        $sheet5->setTitle('Barang Keluar');

        $movementsOut = Peminjaman::with('item.category')->orderBy('waktu_pinjam', 'desc')->get();

        $sheet5->mergeCells('A1:L1');
        $sheet5->setCellValue('A1', 'LAPORAN BARANG KELUAR — ERP NOC SMKN 4 MALANG');
        $sheet5->getStyle('A1')->getFont()->setSize(14)->setBold(true)->setColor(new Color($RED));
        $sheet5->mergeCells('A2:L2');
        $sheet5->setCellValue('A2', 'Total: ' . $movementsOut->count() . ' catatan  |  Dicetak: ' . now()->translatedFormat('d M Y H:i') . ' WIB');
        $sheet5->getStyle('A2')->getFont()->setSize(9)->setItalic(true)->setColor(new Color($GRAY));

        $headers5 = ['No', 'ID Pinjam', 'Peminjam', 'Kelas', 'Kode Barang', 'Nama Barang', 'Kategori', 'Tgl Pinjam', 'Tgl Kembali', 'Status', 'Kondisi Kembali', 'Catatan'];
        $cols5 = ['A','B','C','D','E','F','G','H','I','J','K','L'];
        $this->writeHeaderRow($sheet5, 4, $cols5, $headers5, $RED);

        $row = 5;
        foreach ($movementsOut as $i => $p) {
            $item = $p->item;
            $sheet5->setCellValue("A{$row}", $i + 1);
            $sheet5->setCellValue("B{$row}", 'PJ-' . str_pad($p->id_pinjam, 4, '0', STR_PAD_LEFT));
            $sheet5->setCellValue("C{$row}", $p->nama_peminjam);
            $sheet5->setCellValue("D{$row}", $p->kelas ?? '-');
            $sheet5->setCellValue("E{$row}", $p->item_code);
            $sheet5->setCellValue("F{$row}", $item->name ?? '-');
            $sheet5->setCellValue("G{$row}", $item->category->name ?? '-');
            $sheet5->setCellValue("H{$row}", $p->waktu_pinjam ? $p->waktu_pinjam->format('d-m-Y') : '-');
            $sheet5->setCellValue("I{$row}", $p->waktu_kembali ? $p->waktu_kembali->format('d-m-Y') : '-');
            $sheet5->setCellValue("J{$row}", $p->status_label);
            $sheet5->setCellValue("K{$row}", $p->kondisi_saat_kembali ? ucfirst(str_replace('_', ' ', $p->kondisi_saat_kembali)) : '-');
            $sheet5->setCellValue("L{$row}", $p->catatan ?? '-');

            if ($i % 2 === 1) {
                $sheet5->getStyle("A{$row}:L{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($RED_L);
            }

            $stColor = $p->status === 'dipinjam' ? $AMBER : $GREEN;
            $sheet5->getStyle("J{$row}")->getFont()->setColor(new Color($stColor))->setBold(true);

            $sheet5->getStyle("A{$row}:L{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color($BORDER_C));
            $row++;
        }

        $sheet5->setAutoFilter("A4:L" . ($row - 1));
        $sheet5->freezePane('A5');

        $widths5 = [5, 12, 20, 12, 16, 22, 16, 12, 12, 14, 16, 22];
        foreach ($cols5 as $i => $col) {
            $sheet5->getColumnDimension($col)->setWidth($widths5[$i]);
        }

        // ═══════════════════════════════════════════════════
        // WRITE & DOWNLOAD
        // ═══════════════════════════════════════════════════
        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan-Inventaris-NOC-' . now()->format('Y-m-d') . '.xlsx';

        // Use temp file to avoid memory issues
        $tempFile = tempnam(sys_get_temp_dir(), 'noc_report_');
        $writer->save($tempFile);

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    // ─── Helper: Write styled header row ─────────────────
    private function writeHeaderRow($sheet, int $row, array $cols, array $headers, string $bgColor): void
    {
        $WHITE = 'FFFFFFFF';
        foreach ($cols as $i => $col) {
            $sheet->setCellValue("{$col}{$row}", $headers[$i]);
            $sheet->getStyle("{$col}{$row}")->getFont()->setSize(9)->setBold(true)->setColor(new Color($WHITE));
            $sheet->getStyle("{$col}{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($bgColor);
            $sheet->getStyle("{$col}{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle("{$col}{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color($bgColor));
        }
        $sheet->getRowDimension($row)->setRowHeight(22);
    }

    // ─── Helper: Get next column letter ──────────────────
    private function nextCol(string $col): string
    {
        $col = strtoupper($col);
        $next = chr(ord($col) + 1);
        return $next;
    }
}
