<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemMovement;
use App\Models\Peminjaman;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        // ─── Barang Masuk ───────────────────────────────
        $barangMasuk = ItemMovement::where('type', 'masuk')->count();
        $barangMasukBulanIni = ItemMovement::where('type', 'masuk')
            ->whereMonth('movement_date', now()->month)
            ->whereYear('movement_date', now()->year)
            ->count();
        $barangMasukBulanLalu = ItemMovement::where('type', 'masuk')
            ->whereMonth('movement_date', now()->subMonth()->month)
            ->whereYear('movement_date', now()->subMonth()->year)
            ->count();
        $masukTrend = $barangMasukBulanLalu > 0
            ? round((($barangMasukBulanIni - $barangMasukBulanLalu) / $barangMasukBulanLalu) * 100)
            : ($barangMasukBulanIni > 0 ? 100 : 0);
        $nilaiMasuk = ItemMovement::where('type', 'masuk')
            ->join('items', 'item_movements.item_id', '=', 'items.id')
            ->sum('items.purchase_price');

        // ─── Barang Keluar ──────────────────────────────
        $barangKeluar = Peminjaman::count();
        $barangKeluarBulanIni = Peminjaman::whereMonth('waktu_pinjam', now()->month)
            ->whereYear('waktu_pinjam', now()->year)
            ->count();
        $barangKeluarBulanLalu = Peminjaman::whereMonth('waktu_pinjam', now()->subMonth()->month)
            ->whereYear('waktu_pinjam', now()->subMonth()->year)
            ->count();
        $keluarTrend = $barangKeluarBulanLalu > 0
            ? round((($barangKeluarBulanIni - $barangKeluarBulanLalu) / $barangKeluarBulanLalu) * 100)
            : ($barangKeluarBulanIni > 0 ? 100 : 0);

        // ─── Peminjaman ─────────────────────────────────
        $peminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $peminjamanTerlambat = Peminjaman::where('status', 'dipinjam')
            ->whereDate('waktu_pinjam', '<', now()->subDays(7))
            ->count();
        $peminjamanSelesai = Peminjaman::where('status', 'dikembalikan')->count();
        $totalPeminjam = Peminjaman::distinct('nama_peminjam')->count('nama_peminjam');
        $tingkatPengembalian = $barangKeluar > 0
            ? round(($peminjamanSelesai / $barangKeluar) * 100)
            : 0;

        // ─── Total Aset & Nilai ─────────────────────────
        $totalAset = Item::count();
        $totalNilai = Item::sum('purchase_price');

        // ═══════════════════════════════════════════════════
        // ALGORITMA: Volume Transaksi 30 Hari Terakhir (REAL)
        // ═══════════════════════════════════════════════════
        $endDate = now()->startOfDay();
        $startDate = now()->subDays(29)->startOfDay();
        $labels30Hari = [];
        $dataMovement30Hari = [];
        $dataPeminjaman30Hari = [];
        $dataTotal30Hari = [];
        $maxVolume = 0;

        for ($i = 0; $i < 30; $i++) {
            $currentDate = $startDate->copy()->addDays($i);
            $dateKey = $currentDate->format('Y-m-d');
            $labelKey = $currentDate->format('j');
            if ($i === 0) {
                $labelKey = $currentDate->translatedFormat('M j');
            }
            if ($i === 14) {
                $labelKey = $currentDate->translatedFormat('M j');
            }
            if ($i === 29) {
                $labelKey = $currentDate->translatedFormat('M j');
            }
            $labels30Hari[] = $labelKey;

            $movementCount = ItemMovement::whereDate('movement_date', $dateKey)->count();
            $peminjamanCount = Peminjaman::whereDate('waktu_pinjam', $dateKey)->count();
            $totalCount = $movementCount + $peminjamanCount;

            $dataMovement30Hari[] = $movementCount;
            $dataPeminjaman30Hari[] = $peminjamanCount;
            $dataTotal30Hari[] = $totalCount;

            if ($totalCount > $maxVolume) {
                $maxVolume = $totalCount;
            }
        }
        if ($maxVolume === 0) $maxVolume = 1;

        // ═══════════════════════════════════════════════════
        // ALGORITMA: Stok Menipis (Threshold Berdasarkan Kategori)
        // ═══════════════════════════════════════════════════
        $thresholdUmum = 3;
        $thresholdConsumable = 10;
        $consumableKeywords = ['kabel', 'cable', 'adaptor', 'charger', 'batre', 'baterai', 'mouse', 'keyboard', 'usb', 'flashdisk', 'flash', 'card', 'earphone', 'headset', 'modem', 'router mini', 'converter', 'pen', 'kertas', 'tinta', 'masker', 'sarung', 'pembersih', 'cairan', 'tisu', 'paper'];

        $lowStockQuery = Item::where(function ($q) use ($consumableKeywords) {
            foreach ($consumableKeywords as $kw) {
                $q->orWhereRaw('LOWER(name) LIKE ?', ['%' . strtolower($kw) . '%']);
            }
        })
            ->where('status', '!=', 'dimusnahkan')
            ->where('condition', '!=', 'hilang')
            ->where('quantity', '<=', $thresholdConsumable);

        $nonConsumableLowStock = Item::where(function ($q) use ($consumableKeywords) {
            foreach ($consumableKeywords as $kw) {
                $q->whereRaw('LOWER(name) NOT LIKE ?', ['%' . strtolower($kw) . '%']);
            }
        })
            ->where('status', '!=', 'dimusnahkan')
            ->where('condition', '!=', 'hilang')
            ->whereRaw('quantity <= ' . $thresholdUmum . ' AND quantity > 0');

        $allLowStock = $lowStockQuery->union($nonConsumableLowStock)
            ->orderBy('quantity', 'asc')
            ->limit(20)
            ->get();

        $lowStockCount = $allLowStock->count();
        $lowStockItems = $allLowStock->map(function ($item) {
            return $item->name . ' (' . $item->quantity . ')';
        })->toArray();
        $lowStockPreview = implode(', ', array_slice($lowStockItems, 0, 2));
        if (count($lowStockItems) > 2) {
            $lowStockPreview .= '...';
        }
        if (empty($lowStockPreview)) {
            $lowStockPreview = 'Tidak ada item stok menipis.';
        }

        // ═══════════════════════════════════════════════════
        // ALGORITMA: "Laporan Belum Disetujui" (Pending Actions)
        // Ditafsirkan sebagai TINDAKAN YANG PERLU DIPROSES:
        // 1. Peminjaman terlambat (> 7 hari belum dikembalikan)
        // 2. Barang rusak berat yang belum dimusnahkan
        // 3. Barang dalam maintenance > 14 hari
        // ═══════════════════════════════════════════════════
        $pendingTerlambat = Peminjaman::where('status', 'dipinjam')
            ->whereDate('waktu_pinjam', '<', now()->subDays(7))
            ->count();
        $pendingRusakBerat = Item::where('condition', 'rusak_berat')
            ->where('status', '!=', 'dimusnahkan')
            ->count();
        $pendingMaintenanceLama = Item::where('status', 'maintenance')
            ->whereDate('updated_at', '<', now()->subDays(14))
            ->count();
        $totalPendingActions = $pendingTerlambat + $pendingRusakBerat + $pendingMaintenanceLama;

        $pendingLabels = [];
        if ($pendingTerlambat > 0) $pendingLabels[] = $pendingTerlambat . ' pinjaman terlambat';
        if ($pendingRusakBerat > 0) $pendingLabels[] = $pendingRusakBerat . ' barang rusak berat';
        if ($pendingMaintenanceLama > 0) $pendingLabels[] = $pendingMaintenanceLama . ' maintenance lama';
        $pendingPreview = count($pendingLabels) > 0 ? implode(', ', $pendingLabels) . ' menunggu tindakan.' : 'Semua tindakan selesai diproses.';

        // ═══════════════════════════════════════════════════
        // ALGORITMA: Sinkronisasi Data Terakhir
        // Ambil MAX updated_at dari 5 tabel utama
        // ═══════════════════════════════════════════════════
        $lastSyncCandidates = collect([
            Item::max('updated_at'),
            ItemMovement::max('updated_at'),
            Peminjaman::max('updated_at'),
            Category::max('updated_at'),
            \App\Models\User::max('updated_at'),
        ])->filter();

        $lastSyncTimestamp = $lastSyncCandidates->sortDesc()->first() ?? now();
        $lastSyncTime = \Illuminate\Support\Carbon::parse($lastSyncTimestamp)->setTimezone('Asia/Jakarta');
        $diffDays = now()->diffInDays($lastSyncTime);

        if ($diffDays === 0) {
            $diffHours = now()->diffInHours($lastSyncTime);
            if ($diffHours === 0) {
                $diffMins = max(1, now()->diffInMinutes($lastSyncTime));
                $lastSyncText = 'Terakhir update: Hari ini, ' . $diffMins . ' menit lalu';
            } else {
                $lastSyncText = 'Terakhir update: Hari ini, ' . $lastSyncTime->format('H:i') . ' WIB';
            }
        } elseif ($diffDays === 1) {
            $lastSyncText = 'Terakhir update: Kemarin, ' . $lastSyncTime->format('H:i') . ' WIB';
        } else {
            $lastSyncText = 'Terakhir update: ' . $lastSyncTime->translatedFormat('d M Y, H:i') . ' WIB';
        }

        // ═══════════════════════════════════════════════════
        // ALGORITMA: Kondisi Kritis (untuk label info/sinkronisasi)
        // Status = sukses jika total pending < 5 dan last sync < 1 jam
        // ═══════════════════════════════════════════════════
        $isSystemHealthy = ($totalPendingActions < 5 && $diffDays === 0);
        $syncStatusLabel = $isSystemHealthy ? 'Sinkronisasi Data Berhasil' : 'Pemeriksaan Sistem Diperlukan';

        return view('laporan.laporan', compact(
            'barangMasuk', 'barangMasukBulanIni', 'masukTrend', 'nilaiMasuk',
            'barangKeluar', 'barangKeluarBulanIni', 'keluarTrend',
            'peminjamanAktif', 'peminjamanTerlambat', 'peminjamanSelesai',
            'totalPeminjam', 'tingkatPengembalian',
            'totalAset', 'totalNilai',
            // NEW dynamic data
            'labels30Hari', 'dataMovement30Hari', 'dataPeminjaman30Hari', 'dataTotal30Hari', 'maxVolume',
            'lowStockCount', 'lowStockPreview',
            'totalPendingActions', 'pendingPreview',
            'lastSyncText', 'syncStatusLabel', 'isSystemHealthy'
        ));
    }
}
