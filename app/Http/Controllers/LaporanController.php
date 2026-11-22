<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemMovement;
use App\Models\Peminjaman;
use App\Models\Category;
use App\Models\Procurement;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $dateRange = $request->get('date_range', 'all');

        $now = now();
        $startThisWeek = $now->copy()->startOfWeek();

        // ─── Filter Range Resolution ───────────────────────
        switch ($dateRange) {
            case 'today':
                $rangeLabel = 'Hari Ini (' . $now->translatedFormat('d M Y') . ')';
                $dateConditionMovement = function ($q) use ($now) {
                    $q->whereDate('movement_date', $now->toDateString());
                };
                $dateConditionPeminjaman = function ($q) use ($now) {
                    $q->whereDate('waktu_pinjam', $now->toDateString());
                };
                break;

            case 'week':
                $rangeLabel = 'Minggu Ini (' . $startThisWeek->translatedFormat('d M') . ' - ' . $now->translatedFormat('d M') . ')';
                $dateConditionMovement = function ($q) use ($startThisWeek, $now) {
                    $q->whereBetween('movement_date', [$startThisWeek->toDateString(), $now->toDateString()]);
                };
                $dateConditionPeminjaman = function ($q) use ($startThisWeek, $now) {
                    $q->whereBetween('waktu_pinjam', [$startThisWeek, $now]);
                };
                break;

            case 'month':
                $rangeLabel = 'Bulan Ini (' . $now->translatedFormat('M Y') . ')';
                $dateConditionMovement = function ($q) use ($now) {
                    $q->whereMonth('movement_date', $now->month)->whereYear('movement_date', $now->year);
                };
                $dateConditionPeminjaman = function ($q) use ($now) {
                    $q->whereMonth('waktu_pinjam', $now->month)->whereYear('waktu_pinjam', $now->year);
                };
                break;

            case 'last_month':
                $lastM = $now->copy()->subMonth();
                $rangeLabel = 'Bulan Lalu (' . $lastM->translatedFormat('M Y') . ')';
                $dateConditionMovement = function ($q) use ($lastM) {
                    $q->whereMonth('movement_date', $lastM->month)->whereYear('movement_date', $lastM->year);
                };
                $dateConditionPeminjaman = function ($q) use ($lastM) {
                    $q->whereMonth('waktu_pinjam', $lastM->month)->whereYear('waktu_pinjam', $lastM->year);
                };
                break;

            case 'year':
                $rangeLabel = 'Tahun Ini (' . $now->year . ')';
                $dateConditionMovement = function ($q) use ($now) {
                    $q->whereYear('movement_date', $now->year);
                };
                $dateConditionPeminjaman = function ($q) use ($now) {
                    $q->whereYear('waktu_pinjam', $now->year);
                };
                break;

            default:
                $dateRange = 'all';
                $rangeLabel = 'Semua Periode';
                $dateConditionMovement = null;
                $dateConditionPeminjaman = null;
                break;
        }

        // ─── 1. Barang Masuk ─────────────────────────────
        $barangMasukQuery = ItemMovement::where('type', 'masuk');
        if ($dateConditionMovement) {
            $barangMasukQuery->where($dateConditionMovement);
        }
        $barangMasuk = $barangMasukQuery->count();

        // Nilai barang masuk (dalam periode terpilih)
        $nilaiMasukQuery = ItemMovement::where('type', 'masuk')
            ->join('items', 'item_movements.item_id', '=', 'items.id');
        if ($dateConditionMovement) {
            $nilaiMasukQuery->where($dateConditionMovement);
        }
        $nilaiMasuk = (float) $nilaiMasukQuery->sum('items.purchase_price');

        // Statistik bulan ini & bulan lalu untuk trend
        $barangMasukBulanIni = ItemMovement::where('type', 'masuk')
            ->whereMonth('movement_date', $now->month)
            ->whereYear('movement_date', $now->year)
            ->count();
        $barangMasukBulanLalu = ItemMovement::where('type', 'masuk')
            ->whereMonth('movement_date', $now->copy()->subMonth()->month)
            ->whereYear('movement_date', $now->copy()->subMonth()->year)
            ->count();
        $masukTrend = $barangMasukBulanLalu > 0
            ? round((($barangMasukBulanIni - $barangMasukBulanLalu) / $barangMasukBulanLalu) * 100)
            : ($barangMasukBulanIni > 0 ? 100 : 0);

        // ─── 2. Barang Keluar / Peminjaman ───────────────
        $barangKeluarQuery = Peminjaman::query();
        if ($dateConditionPeminjaman) {
            $barangKeluarQuery->where($dateConditionPeminjaman);
        }
        $barangKeluar = $barangKeluarQuery->count();

        $barangKeluarBulanIni = Peminjaman::whereMonth('waktu_pinjam', $now->month)
            ->whereYear('waktu_pinjam', $now->year)
            ->count();
        $barangKeluarBulanLalu = Peminjaman::whereMonth('waktu_pinjam', $now->copy()->subMonth()->month)
            ->whereYear('waktu_pinjam', $now->copy()->subMonth()->year)
            ->count();
        $keluarTrend = $barangKeluarBulanLalu > 0
            ? round((($barangKeluarBulanIni - $barangKeluarBulanLalu) / $barangKeluarBulanLalu) * 100)
            : ($barangKeluarBulanIni > 0 ? 100 : 0);

        // ─── 3. Peminjaman Aktif & Pengembalian ───────────
        $peminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $peminjamanTerlambat = Peminjaman::where('status', 'dipinjam')
            ->whereDate('waktu_pinjam', '<', $now->copy()->subDays(7))
            ->count();

        $peminjamanSelesaiQuery = Peminjaman::where('status', 'dikembalikan');
        if ($dateConditionPeminjaman) {
            $peminjamanSelesaiQuery->where($dateConditionPeminjaman);
        }
        $peminjamanSelesai = $peminjamanSelesaiQuery->count();

        $totalPeminjamQuery = Peminjaman::query();
        if ($dateConditionPeminjaman) {
            $totalPeminjamQuery->where($dateConditionPeminjaman);
        }
        $totalPeminjam = $totalPeminjamQuery->distinct('nama_peminjam')->count('nama_peminjam');

        $tingkatPengembalian = $barangKeluar > 0
            ? round(($peminjamanSelesai / $barangKeluar) * 100)
            : 0;

        // ─── 4. Total Aset & Nilai Keseluruhan ───────────
        $totalAset = Item::count();
        $totalNilai = (float) Item::sum('purchase_price');

        // ═══════════════════════════════════════════════════
        // 5. Volume Transaksi 30 Hari Terakhir (REAL & FAST)
        // ═══════════════════════════════════════════════════
        $endDate = $now->copy()->startOfDay();
        $startDate = $now->copy()->subDays(29)->startOfDay();

        $movements30Days = ItemMovement::selectRaw('DATE(movement_date) as tgl, COUNT(*) as cnt')
            ->whereDate('movement_date', '>=', $startDate->toDateString())
            ->whereDate('movement_date', '<=', $endDate->toDateString())
            ->groupBy('tgl')
            ->pluck('cnt', 'tgl')
            ->all();

        $peminjaman30Days = Peminjaman::selectRaw('DATE(waktu_pinjam) as tgl, COUNT(*) as cnt')
            ->whereDate('waktu_pinjam', '>=', $startDate->toDateString())
            ->whereDate('waktu_pinjam', '<=', $endDate->toDateString())
            ->groupBy('tgl')
            ->pluck('cnt', 'tgl')
            ->all();

        $chartItems = [];
        $maxVolume = 0;
        $total30Hari = 0;

        for ($i = 0; $i < 30; $i++) {
            $curr = $startDate->copy()->addDays($i);
            $key = $curr->format('Y-m-d');
            $mov = (int) ($movements30Days[$key] ?? 0);
            $pem = (int) ($peminjaman30Days[$key] ?? 0);
            $tot = $mov + $pem;

            $total30Hari += $tot;
            if ($tot > $maxVolume) {
                $maxVolume = $tot;
            }

            $showAxisLabel = ($i === 0 || $i === 7 || $i === 14 || $i === 21 || $i === 29);

            $chartItems[] = [
                'date' => $key,
                'full_date' => $curr->translatedFormat('d M Y'),
                'axis_label' => $showAxisLabel ? $curr->translatedFormat('j M') : '',
                'movement' => $mov,
                'peminjaman' => $pem,
                'total' => $tot,
                'is_today' => ($i === 29),
            ];
        }

        if ($maxVolume === 0) $maxVolume = 1;
        $rataRata30Hari = round($total30Hari / 30, 1);
        $todayVolume = $chartItems[29]['total'] ?? 0;

        // ═══════════════════════════════════════════════════
        // 6. Perhatian Sistem: Stok Menipis (REAL AVAILABLE STOCK)
        // Dihitung dari unit berstatus 'tersedia' <= 2 unit per model/nama barang
        // ═══════════════════════════════════════════════════
        $lowStockRecords = Item::where('status', 'tersedia')
            ->selectRaw('name, count(*) as stock')
            ->groupBy('name')
            ->having('stock', '<=', 2)
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        $lowStockCount = Item::where('status', 'tersedia')
            ->selectRaw('name, count(*) as stock')
            ->groupBy('name')
            ->having('stock', '<=', 2)
            ->get()
            ->count();

        $lowStockItems = $lowStockRecords->map(function ($item) {
            return $item->name . ' (' . $item->stock . ' unit)';
        })->toArray();

        $lowStockPreview = count($lowStockItems) > 0 ? implode(', ', array_slice($lowStockItems, 0, 2)) : '';
        if (count($lowStockItems) > 2) {
            $lowStockPreview .= '...';
        }
        if (empty($lowStockPreview)) {
            $lowStockPreview = 'Semua stok unit tersedia dalam batas aman.';
        }

        // ═══════════════════════════════════════════════════
        // 7. Perhatian Sistem: Tindakan Perlu Diproses (REAL)
        // ═══════════════════════════════════════════════════
        $pendingTerlambat = Peminjaman::where('status', 'dipinjam')
            ->whereDate('waktu_pinjam', '<', $now->copy()->subDays(7))
            ->count();

        $pendingRusak = Item::whereIn('condition', ['rusak_berat', 'rusak_ringan'])
            ->where('status', '!=', 'dimusnahkan')
            ->count();

        $pendingMaintenance = Item::where('status', 'maintenance')
            ->count();

        $pendingProcurement = class_exists(Procurement::class)
            ? Procurement::where('status', 'pending')->count()
            : 0;

        $totalPendingActions = $pendingTerlambat + $pendingRusak + $pendingMaintenance + $pendingProcurement;

        $pendingLabels = [];
        if ($pendingTerlambat > 0) $pendingLabels[] = $pendingTerlambat . ' pinjaman terlambat';
        if ($pendingMaintenance > 0) $pendingLabels[] = $pendingMaintenance . ' sedang maintenance';
        if ($pendingRusak > 0) $pendingLabels[] = $pendingRusak . ' barang rusak';
        if ($pendingProcurement > 0) $pendingLabels[] = $pendingProcurement . ' pengadaan pending';

        $pendingPreview = count($pendingLabels) > 0
            ? implode(', ', array_slice($pendingLabels, 0, 2)) . (count($pendingLabels) > 2 ? '...' : ' perlu ditindaklanjuti.')
            : 'Semua proses inventaris berjalan normal.';

        // ═══════════════════════════════════════════════════
        // 8. Sinkronisasi Data Terakhir (REAL UPDATED_AT)
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
        $diffDays = $now->diffInDays($lastSyncTime);

        if ($diffDays === 0) {
            $diffHours = $now->diffInHours($lastSyncTime);
            if ($diffHours === 0) {
                $diffMins = max(1, $now->diffInMinutes($lastSyncTime));
                $lastSyncText = 'Terakhir update: ' . $diffMins . ' menit lalu';
            } else {
                $lastSyncText = 'Terakhir update: Hari ini, ' . $lastSyncTime->format('H:i') . ' WIB';
            }
        } elseif ($diffDays === 1) {
            $lastSyncText = 'Terakhir update: Kemarin, ' . $lastSyncTime->format('H:i') . ' WIB';
        } else {
            $lastSyncText = 'Terakhir update: ' . $lastSyncTime->translatedFormat('d M Y, H:i') . ' WIB';
        }

        $isSystemHealthy = ($peminjamanTerlambat === 0 && $pendingMaintenance < 5);
        $syncStatusLabel = $isSystemHealthy ? 'Status Inventaris Sehat' : 'Pemeriksaan Sistem Diperlukan';

        return view('laporan.laporan', compact(
            'dateRange', 'rangeLabel',
            'barangMasuk', 'barangMasukBulanIni', 'masukTrend', 'nilaiMasuk',
            'barangKeluar', 'barangKeluarBulanIni', 'keluarTrend',
            'peminjamanAktif', 'peminjamanTerlambat', 'peminjamanSelesai',
            'totalPeminjam', 'tingkatPengembalian',
            'totalAset', 'totalNilai',
            // Chart Data
            'chartItems', 'maxVolume', 'rataRata30Hari', 'todayVolume',
            // System alerts
            'lowStockCount', 'lowStockPreview',
            'totalPendingActions', 'pendingPreview',
            'lastSyncText', 'syncStatusLabel', 'isSystemHealthy'
        ));
    }
}
