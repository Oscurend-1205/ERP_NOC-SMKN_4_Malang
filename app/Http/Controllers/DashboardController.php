<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemMovement;
use App\Models\Location;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard.
     */
    public function index()
    {
        $conditions = Item::selectRaw("`condition`, count(*) as total")
            ->groupBy('condition')
            ->pluck('total', 'condition');

        $totalItems = Item::count();
        $totalCategories = Category::count();
        $totalLocations = Location::count();
        $cond = collect($conditions);
        $itemsBaik = $cond->get('baik', 0);
        $itemsRusak = $cond->get('rusak_ringan', 0) + $cond->get('rusak_berat', 0);
        $itemsMaintenance = Item::where('status', 'maintenance')->count();
        
        $totalValue = Item::sum('purchase_price') ?? 0;
        $recentMovementsQuery = ItemMovement::with(['item', 'user', 'fromLocation', 'toLocation']);
        if (auth()->user()->isJurusan()) {
            $recentMovementsQuery->where('user_id', auth()->id());
        }
        $recentMovements = $recentMovementsQuery->latest()->limit(10)->get();

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
        $currentYear = now()->year;
        $monthlyIncoming = ItemMovement::selectRaw('MONTH(created_at) as month, SUM(quantity) as total')
            ->where('type', 'masuk')
            ->whereYear('created_at', $currentYear)
            ->groupByRaw('MONTH(created_at)')
            ->pluck('total', 'month');
        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[] = (int) ($monthlyIncoming[$m] ?? 0);
        }
        $availableItems = Item::where('quantity', '>', 0)->get();

        return view('dashboard', compact(
            'totalItems',
            'totalCategories',
            'totalLocations',
            'itemsBaik',
            'itemsRusak',
            'itemsMaintenance',
            'totalValue',
            'recentMovements',
            'itemsByCategory',
            'itemsByLocation',
            'conditionStats',
            'itemsEnteredToday',
            'availableItems',
            'monthlyData',
            'currentYear'
        ));
    }
}

