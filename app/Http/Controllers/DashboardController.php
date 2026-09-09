<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemMovement;
use App\Models\Location;
use App\Models\Notification;
use App\Models\Procurement;
use App\Models\StockTake;
use App\Models\Perawatan;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard.
     */
    public function index(Request $request)
    {
        // Filter parameters
        $filterYear = $request->get('year', now()->year);
        $filterCategory = $request->get('category');
        $filterLocation = $request->get('location');

        $conditions = Item::selectRaw("`condition`, count(*) as total")
            ->groupBy('condition')
            ->pluck('total', 'condition');

        $totalItems = Item::count();
        $totalCategories = Category::count();
        $totalLocations = Location::count();
        $cond = collect($conditions);
        $itemsBaik = $cond->get('baik', 0);
        $itemsRusak = $cond->get('rusak_ringan', 0) + $cond->get('rusak_berat', 0);
        $itemsTersedia = Item::where('status', 'tersedia')->count();
        $itemsDipinjam = Item::where('status', 'dipinjam')->count();
        $itemsMaintenance = Item::where('status', 'maintenance')->count();

        $totalValue = Item::sum('purchase_price') ?? 0;
        $recentMovementsQuery = ItemMovement::with(['item', 'user', 'fromLocation', 'toLocation']);
        if (auth()->user()->isJurusan()) {
            $recentMovementsQuery->where('user_id', auth()->id());
        }
        $recentMovements = $recentMovementsQuery->latest()->limit(6)->get();

        $itemsByCategory = Category::withCount('items')
            ->orderBy('items_count', 'desc')
            ->limit(6)
            ->get();

        $itemsByLocation = Location::withCount('items')
            ->orderBy('items_count', 'desc')
            ->limit(6)
            ->get();
        $conditionStats = [
            'baik' => $itemsBaik,
            'rusak_ringan' => $cond->get('rusak_ringan', 0),
            'rusak_berat' => $cond->get('rusak_berat', 0),
            'hilang' => $cond->get('hilang', 0),
        ];
        $itemsEnteredToday = ItemMovement::where('type', 'masuk')
            ->whereDate('created_at', today())
            ->count();
        $currentYear = $filterYear;

        // Monthly data with filters
        $monthlyIncomingQuery = ItemMovement::selectRaw('MONTH(created_at) as month, SUM(quantity) as total')
            ->where('type', 'masuk')
            ->whereYear('created_at', $currentYear);

        if ($filterCategory) {
            $monthlyIncomingQuery->whereHas('item', function($q) use ($filterCategory) {
                $q->where('category_id', $filterCategory);
            });
        }
        if ($filterLocation) {
            $monthlyIncomingQuery->whereHas('item', function($q) use ($filterLocation) {
                $q->where('location_id', $filterLocation);
            });
        }

        $monthlyIncoming = $monthlyIncomingQuery->groupByRaw('MONTH(created_at)')->pluck('total', 'month');
        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[] = (int) ($monthlyIncoming[$m] ?? 0);
        }

        // Monthly outgoing data
        $monthlyOutgoingQuery = ItemMovement::selectRaw('MONTH(created_at) as month, SUM(quantity) as total')
            ->where('type', 'keluar')
            ->whereYear('created_at', $currentYear);

        if ($filterCategory) {
            $monthlyOutgoingQuery->whereHas('item', function($q) use ($filterCategory) {
                $q->where('category_id', $filterCategory);
            });
        }
        if ($filterLocation) {
            $monthlyOutgoingQuery->whereHas('item', function($q) use ($filterLocation) {
                $q->where('location_id', $filterLocation);
            });
        }

        $monthlyOutgoing = $monthlyOutgoingQuery->groupByRaw('MONTH(created_at)')->pluck('total', 'month');
        $monthlyOutgoingData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyOutgoingData[] = (int) ($monthlyOutgoing[$m] ?? 0);
        }

        // Monthly maintenance data
        $monthlyMaintenanceQuery = ItemMovement::selectRaw('MONTH(created_at) as month, SUM(quantity) as total')
            ->where('type', 'maintenance')
            ->whereYear('created_at', $currentYear);

        if ($filterCategory) {
            $monthlyMaintenanceQuery->whereHas('item', function($q) use ($filterCategory) {
                $q->where('category_id', $filterCategory);
            });
        }
        if ($filterLocation) {
            $monthlyMaintenanceQuery->whereHas('item', function($q) use ($filterLocation) {
                $q->where('location_id', $filterLocation);
            });
        }

        $monthlyMaintenance = $monthlyMaintenanceQuery->groupByRaw('MONTH(created_at)')->pluck('total', 'month');
        $monthlyMaintenanceData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyMaintenanceData[] = (int) ($monthlyMaintenance[$m] ?? 0);
        }

        // Available years for filter
        $availableYears = ItemMovement::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        $availableItems = Item::where('quantity', '>', 0)->get();

        $recentNotifications = Notification::forUser(auth()->id())
            ->latest()
            ->limit(5)
            ->get();

        $activeStockTake = StockTake::with('location')
            ->whereIn('status', ['draft', 'in_progress'])
            ->latest()
            ->first();

        // Statistik Pengadaan Alat (Procurement)
        $procurementStats = [
            'pending'       => Procurement::where('status', 'pending')->count(),
            'in_procurement'=> Procurement::where('status', 'in_procurement')->count(),
            'total_pending_cost' => Procurement::where('status', 'pending')->sum('total_estimated_cost'),
        ];

        // Statistik Perawatan
        $perawatanStats = [
            'total' => Perawatan::count(),
            'menunggu' => Perawatan::where('status', 'menunggu')->count(),
            'proses' => Perawatan::where('status', 'proses')->count(),
            'selesai' => Perawatan::where('status', 'selesai')->count(),
        ];

        // Statistik Peminjaman
        $peminjamanStats = [
            'active' => Peminjaman::where('status', 'dipinjam')->count(),
            'overdue' => Peminjaman::where('status', 'dipinjam')
                ->where('waktu_pinjam', '<', now()->subDays(7))->count(), // asumsi max 7 hari
        ];

        return view('dashboard', compact(
            'totalItems',
            'totalCategories',
            'totalLocations',
            'itemsBaik',
            'itemsRusak',
            'itemsTersedia',
            'itemsDipinjam',
            'itemsMaintenance',
            'totalValue',
            'recentMovements',
            'itemsByCategory',
            'itemsByLocation',
            'conditionStats',
            'itemsEnteredToday',
            'availableItems',
            'monthlyData',
            'monthlyOutgoingData',
            'monthlyMaintenanceData',
            'currentYear',
            'availableYears',
            'filterCategory',
            'filterLocation',
            'recentNotifications',
            'activeStockTake',
            'procurementStats',
            'perawatanStats',
            'peminjamanStats'
        ));
    }
}

