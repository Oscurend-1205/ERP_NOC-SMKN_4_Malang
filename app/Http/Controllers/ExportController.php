<?php

namespace App\Http\Controllers;

use App\Models\ItemMovement;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    /**
     * Export Barang Masuk - CSV (Excel compatible)
     */
    public function barangMasukCsv(Request $request)
    {
        $query = ItemMovement::with(['item.category', 'user', 'toLocation'])
            ->where('type', 'masuk');

        $this->applyDateFilter($query, $request);

        $movements = $query->orderBy('movement_date', 'desc')->get();

        $filename = 'barang-masuk-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($movements) {
            $file = fopen('php://output', 'w');
            // BOM for UTF-8 in Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['No', 'Tanggal Masuk', 'Kode Barang', 'Nama Barang', 'Kategori', 'Merek', 'Kondisi', 'Jumlah', 'Lokasi Tujuan', 'Dicatat Oleh', 'Catatan']);

            foreach ($movements as $i => $m) {
                $item = $m->item;
                fputcsv($file, [
                    $i + 1,
                    $m->movement_date ? $m->movement_date->format('d-m-Y') : '-',
                    $item->code ?? '-',
                    $item->name ?? '-',
                    $item->category->name ?? '-',
                    $item->brand ?? '-',
                    $item->condition_label ?? '-',
                    $m->quantity,
                    $m->toLocation->name ?? '-',
                    $m->user->name ?? '-',
                    $m->notes ?? '-',
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export Barang Masuk - Print-ready HTML (for PDF via browser print)
     */
    public function barangMasukPrint(Request $request)
    {
        $query = ItemMovement::with(['item.category', 'user', 'toLocation'])
            ->where('type', 'masuk');

        $this->applyDateFilter($query, $request);
        $movements = $query->orderBy('movement_date', 'desc')->get();

        return view('exports.barang-masuk', compact('movements'));
    }

    /**
     * Export Barang Keluar - CSV (Excel compatible)
     */
    public function barangKeluarCsv(Request $request)
    {
        $query = Peminjaman::with('item.category');
        $this->applyPeminjamanDateFilter($query, $request);

        $peminjamans = $query->orderBy('waktu_pinjam', 'desc')->get();

        $filename = 'barang-keluar-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($peminjamans) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['No', 'ID Pinjam', 'Nama Peminjam', 'Jurusan/Kelas', 'Kode Barang', 'Nama Barang', 'Kategori', 'Tgl Pinjam', 'Tgl Kembali', 'Status', 'Kondisi Saat Kembali', 'Catatan']);

            foreach ($peminjamans as $i => $p) {
                $item = $p->item;
                fputcsv($file, [
                    $i + 1,
                    'PJ-' . str_pad($p->id_pinjam, 4, '0', STR_PAD_LEFT),
                    $p->nama_peminjam,
                    $p->kelas ?? '-',
                    $p->item_code,
                    $item->name ?? '-',
                    $item->category->name ?? '-',
                    $p->waktu_pinjam ? $p->waktu_pinjam->format('d-m-Y') : '-',
                    $p->waktu_kembali ? $p->waktu_kembali->format('d-m-Y') : '-',
                    $p->status_label,
                    $this->kondisiLabel($p->kondisi_saat_kembali),
                    $p->catatan ?? '-',
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export Barang Keluar - Print-ready HTML (for PDF via browser print)
     */
    public function barangKeluarPrint(Request $request)
    {
        $query = Peminjaman::with('item.category');
        $this->applyPeminjamanDateFilter($query, $request);
        $peminjamans = $query->orderBy('waktu_pinjam', 'desc')->get();

        return view('exports.barang-keluar', compact('peminjamans'));
    }

    /**
     * Export Peminjaman - CSV (Excel compatible)
     */
    public function peminjamanCsv(Request $request)
    {
        $query = Peminjaman::with('item.category');
        $this->applyPeminjamanDateFilter($query, $request);

        if ($request->filled('jurusan') && $request->jurusan !== 'Semua Jurusan') {
            $query->where('kelas', 'like', '%' . $request->jurusan . '%');
        }

        $peminjamans = $query->orderBy('waktu_pinjam', 'desc')->get();

        $filename = 'data-peminjaman-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($peminjamans) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['No', 'ID Pinjam', 'Nama Peminjam', 'Jurusan/Kelas', 'Kode Barang', 'Nama Barang', 'Kategori', 'Tgl Pinjam', 'Tgl Kembali', 'Status', 'Kondisi Saat Kembali', 'Catatan']);

            foreach ($peminjamans as $i => $p) {
                $item = $p->item;
                fputcsv($file, [
                    $i + 1,
                    'PJ-' . str_pad($p->id_pinjam, 4, '0', STR_PAD_LEFT),
                    $p->nama_peminjam,
                    $p->kelas ?? '-',
                    $p->item_code,
                    $item->name ?? '-',
                    $item->category->name ?? '-',
                    $p->waktu_pinjam ? $p->waktu_pinjam->format('d-m-Y') : '-',
                    $p->waktu_kembali ? $p->waktu_kembali->format('d-m-Y') : '-',
                    $p->status_label,
                    $this->kondisiLabel($p->kondisi_saat_kembali),
                    $p->catatan ?? '-',
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export Peminjaman - Print-ready HTML (for PDF via browser print)
     */
    public function peminjamanPrint(Request $request)
    {
        $query = Peminjaman::with('item.category');
        $this->applyPeminjamanDateFilter($query, $request);

        if ($request->filled('jurusan') && $request->jurusan !== 'Semua Jurusan') {
            $query->where('kelas', 'like', '%' . $request->jurusan . '%');
        }

        $peminjamans = $query->orderBy('waktu_pinjam', 'desc')->get();

        return view('exports.peminjaman', compact('peminjamans'));
    }

    // ---- Helpers ----

    private function applyDateFilter($query, Request $request): void
    {
        if ($request->filled('date_range')) {
            match ($request->date_range) {
                'today' => $query->whereDate('movement_date', now()->toDateString()),
                'week'  => $query->whereBetween('movement_date', [now()->startOfWeek(), now()->endOfWeek()]),
                'month' => $query->whereMonth('movement_date', now()->month)->whereYear('movement_date', now()->year),
                default => null,
            };
        }
    }

    private function applyPeminjamanDateFilter($query, Request $request): void
    {
        if ($request->filled('date_range')) {
            match ($request->date_range) {
                'today' => $query->whereDate('waktu_pinjam', now()->toDateString()),
                'week'  => $query->whereBetween('waktu_pinjam', [now()->startOfWeek(), now()->endOfWeek()]),
                'month' => $query->whereMonth('waktu_pinjam', now()->month)->whereYear('waktu_pinjam', now()->year),
                default => null,
            };
        }
    }

    private function kondisiLabel(?string $kondisi): string
    {
        return match ($kondisi) {
            'baik' => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat' => 'Rusak Berat',
            'hilang' => 'Hilang',
            default => '-',
        };
    }
}
