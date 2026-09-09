<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Peminjaman;
use App\Models\Location;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        if (!$request->has('q') || strlen($request->q) < 2) {
            return response()->json(['results' => []]);
        }

        $query = strtolower($request->q);
        $results = [];

        if (Auth::user()->can('view', Item::class) || Auth::user()->role === 'Superadmin' || Auth::user()->role === 'Admin') {
            $items = Item::with(['category', 'location'])
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('code', 'like', "%{$query}%")
                      ->orWhere('serial_number', 'like', "%{$query}%");
                })
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'type' => 'item',
                        'url' => route('items.show', $item->id),
                        'extra' => $item->code,
                    ];
                });
            $results = $results->concat($items);
        }

        if (Auth::check()) {
            $peminjamans = Peminjaman::with(['item'])
                ->where(function ($q) use ($query) {
                    $q->where('item_code', 'like', "%{$query}%")
                      ->orWhere('catatan', 'like', "%{$query}%");
                })
                ->whereIn('status', ['pending', 'active'])
                ->limit(5)
                ->get()
                ->map(function ($p) {
                    return [
                        'id' => $p->id_pinjam,
                        'name' => $p->item_code ?? 'Peminjaman #' . $p->id_pinjam,
                        'type' => 'peminjaman',
                        'url' => route('peminjaman.index'),
                    ];
                });
            $results = $results->concat($peminjamans);
        }

        $locations = Location::where('name', 'like', "%{$query}%")
            ->orWhere('code', 'like', "%{$query}%")
            ->limit(3)
            ->get()
            ->map(function ($l) {
                return [
                    'id' => $l->id,
                    'name' => $l->name,
                    'type' => 'location',
                    'url' => route('locations.index'),
                ];
            });
        $results = $results->concat($locations);

        $categories = Category::where('name', 'like', "%{$query}%")
            ->limit(3)
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'name' => $c->name,
                    'type' => 'category',
                    'url' => route('categories.index'),
                ];
            });
        $results = $results->concat($categories);

        return response()->json(['results' => $results]);
    }
}
