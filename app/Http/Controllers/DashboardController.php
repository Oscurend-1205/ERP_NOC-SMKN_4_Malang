<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemMovement;
use App\Models\Location;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard.
     */
    public function index()
    {
        // Statistik Utama - Menggunakan aggregate query tunggal untuk performa lebih baik
        $conditions = Item::selectRaw("`condition`, count(*) as total")
            ->groupBy('condition')
            ->pluck('total', 'condition');

        $totalItems = Item::count();
        $totalCategories = Category::count();
        $totalLocations = Location::count();
        
        // Memastikan nilai default 0 jika data tidak ditemukan (menggunakan collect() agar lebih aman)
        $cond = collect($conditions);
        $itemsBaik = $cond->get('baik', 0);
        $itemsRusak = $cond->get('rusak_ringan', 0) + $cond->get('rusak_berat', 0);
        $itemsMaintenance = Item::where('status', 'maintenance')->count();
        
        $totalValue = Item::sum('purchase_price') ?? 0;

        // Data List & Chart
        $recentMovements = ItemMovement::with(['item', 'user', 'fromLocation', 'toLocation'])
            ->latest()
            ->limit(10)
            ->get();

        $itemsByCategory = Category::withCount('items')
            ->orderBy('items_count', 'desc')
            ->limit(6)
            ->get();

        $itemsByLocation = Location::withCount('items')
            ->orderBy('items_count', 'desc')
            ->limit(6)
            ->get();

        // Statistik Kondisi untuk Chart di View
        $conditionStats = [
            'baik' => $itemsBaik,
            'rusak_ringan' => $cond->get('rusak_ringan', 0),
            'rusak_berat' => $cond->get('rusak_berat', 0),
            'hilang' => $cond->get('hilang', 0),
        ];

        // Statistik Tambahan: Pergerakan hari ini
        $itemsEnteredToday = ItemMovement::where('type', 'masuk')
            ->whereDate('created_at', today())
            ->count();

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
            'itemsEnteredToday'
        ));
    }
}
