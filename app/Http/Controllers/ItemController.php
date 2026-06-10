<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemMovement;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    /**
     * Tampilkan semua barang dengan filter.
     */
    public function index(Request $request)
    {
        $query = Item::with(['category', 'location']);

        // Filter berdasarkan kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter berdasarkan lokasi
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        // Filter berdasarkan kondisi
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        // Kelompokkan barang berdasarkan jenisnya (prefix kode, nama, dsb)
        // COUNT(*) = jumlah unit fisik nyata di database (1 row = 1 unit)
        $items = $query->select(
                'name', 
                'brand', 
                'model', 
                'category_id', 
                'location_id', 
                \DB::raw('`condition`'), 
                \DB::raw('`status`'), 
                \DB::raw('SUBSTRING_INDEX(code, "-", 1) as prefix'), 
                \DB::raw('COUNT(*) as total_stock'),
                \DB::raw('MIN(id) as id')
            )
            ->groupBy('name', 'brand', 'model', 'category_id', 'location_id', \DB::raw('`condition`'), \DB::raw('`status`'), \DB::raw('SUBSTRING_INDEX(code, "-", 1)'))
            ->orderBy('name', 'asc')
            ->paginate(15);

        $categories = Category::all();
        $locations = Location::all();

        // Ambil daftar barang unik berdasarkan nama, merk, dan model untuk dropdown "Barang Sudah Ada"
        $existingItems = Item::select('name', 'brand', 'model', 'category_id')
            ->groupBy('name', 'brand', 'model', 'category_id')
            ->orderBy('name', 'asc')
            ->get();

        return view('items.index', compact('items', 'categories', 'locations', 'existingItems'));
    }

    /**
     * AJAX endpoint to get specific units of a grouped item.
     */
    public function units(Request $request)
    {
        $units = Item::where('name', $request->name)
            ->where('location_id', $request->location_id)
            ->where('condition', $request->condition)
            ->where('status', $request->status)
            ->orderBy('code', 'asc')
            ->get();
            
        return response()->json($units);
    }

    /**
     * Tampilkan form tambah barang.
     */
    public function create()
    {
        $categories = Category::all();
        $locations = Location::all();
        return view('items.create', compact('categories', 'locations'));
    }

    /**
     * Tampilkan form input barang via Scanner QR.
     */
    public function scanInput()
    {
        $categories = Category::all();
        $locations = Location::all();
        return view('items.scan-input', compact('categories', 'locations'));
    }

    /**
     * Simpan barang dari Scanner QR.
     */
    public function storeScanInput(Request $request)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|max:100|unique:items',
            'name' => 'required|string|max:255',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'category_id' => 'required|exists:categories,id',
            'purchase_date' => 'required|date',
            'location_id' => 'required|exists:locations,id',
            'quantity' => 'nullable|integer|min:1',
            'status' => 'nullable|in:tersedia,dipinjam,maintenance,dimusnahkan',
        ]);

        // Beri default value untuk field yang tidak ada di form scanner
        $validated['quantity'] = $validated['quantity'] ?? 1;
        $validated['status'] = $validated['status'] ?? 'tersedia';

        // Auto-generate code from category prefix if not provided manually
        if (empty($validated['code'])) {
            $validated['code'] = $this->generateItemCode($validated['category_id']);
        }

        Item::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil disimpan!',
        ]);
    }

    /**
     * Generate unique item code based on category prefix.
     * Format: <PREFIX>-<NOMOR> (e.g., PRF-0001, SBN-0002)
     * Uses last_code_number from categories table for atomic sequence.
     */
    private function generateItemCode($categoryId)
    {
        $category = Category::findOrFail($categoryId);

        if (empty($category->prefix)) {
            throw new \Exception("Kategori '{$category->name}' belum memiliki prefix kode. Silakan set prefix di Data Master Kategori.");
        }

        // Atomically increment last_code_number to prevent race conditions
        $newNumber = DB::transaction(function () use ($category) {
            // Lock the category row
            $locked = Category::where('id', $category->id)->lockForUpdate()->first();
            $next = $locked->last_code_number + 1;
            $locked->update(['last_code_number' => $next]);
            return $next;
        });

        return $category->prefix . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Simpan barang baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'status' => 'required|in:tersedia,dipinjam,maintenance,dimusnahkan',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|string',
            'notes' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Clean purchase_price from dots if present
        if (isset($validated['purchase_price'])) {
            $validated['purchase_price'] = str_replace('.', '', $validated['purchase_price']);
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        $quantity = $validated['quantity'];
        
        // Untuk setiap unit, kita buat record tersendiri dengan kode unik
        // Pastikan quantity untuk tiap record adalah 1
        $validated['quantity'] = 1;
        
        for ($i = 0; $i < $quantity; $i++) {
            $validated['code'] = $this->generateItemCode($validated['category_id']);
            Item::create($validated);
        }

        return redirect()->route('items.index')
            ->with('success', $quantity . ' Barang berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail barang.
     */
    public function show(Item $item)
    {
        $item->load(['category', 'location', 'movements.user', 'movements.fromLocation', 'movements.toLocation']);
        return view('items.show', compact('item'));
    }

    /**
     * Tampilkan form edit barang.
     */
    public function edit(Item $item)
    {
        $categories = Category::all();
        $locations = Location::all();
        return view('items.edit', compact('item', 'categories', 'locations'));
    }

    /**
     * Update barang.
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:items,code,' . $item->id,
            'serial_number' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'status' => 'required|in:tersedia,dipinjam,maintenance,dimusnahkan',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|string',
            'notes' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Clean purchase_price from dots if present
        if (isset($validated['purchase_price'])) {
            $validated['purchase_price'] = str_replace('.', '', $validated['purchase_price']);
        }

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        $item->update($validated);

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Hapus barang.
     */
    public function destroy(Item $item)
    {
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil dihapus.');
    }

    /**
     * Tampilkan data barang masuk.
     */
    public function barangMasuk(Request $request)
    {
        $query = ItemMovement::with(['item.category', 'user', 'toLocation'])
            ->where('type', 'masuk');

        // Filter rentang tanggal
        if ($request->filled('date_range')) {
            $today = now()->toDateString();
            match ($request->date_range) {
                'today' => $query->whereDate('movement_date', $today),
                'week'  => $query->whereBetween('movement_date', [now()->startOfWeek(), now()->endOfWeek()]),
                'month' => $query->whereMonth('movement_date', now()->month)
                                   ->whereYear('movement_date', now()->year),
                default => null,
            };
        }

        // Filter kondisi barang
        if ($request->filled('condition')) {
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('condition', $request->condition);
            });
        }

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('item', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $movements = $query->orderBy('movement_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Daftar barang unik untuk dropdown di modal
        $items = Item::select('id', 'name', 'code', 'brand', 'model')
            ->orderBy('name', 'asc')
            ->get();

        $locations = Location::all();

        return view('items.barang-masuk.barang-masuk', compact('movements', 'items', 'locations'));
    }

    /**
     * Simpan data barang masuk baru.
     */
    public function storeBarangMasuk(Request $request)
    {
        $validated = $request->validate([
            'item_id'        => 'required|exists:items,id',
            'quantity'       => 'required|integer|min:1',
            'movement_date'  => 'required|date',
            'to_location_id' => 'nullable|exists:locations,id',
            'notes'          => 'nullable|string|max:500',
        ]);

        $validated['type']    = 'masuk';
        $validated['user_id'] = Auth::id();

        ItemMovement::create($validated);

        // Increment stok barang
        $item = Item::find($validated['item_id']);
        $item->increment('quantity', $validated['quantity']);

        // Update lokasi jika diisi
        if (!empty($validated['to_location_id'])) {
            $item->update(['location_id' => $validated['to_location_id']]);
        }

        return redirect()->route('items.barang-masuk')
            ->with('success', 'Data barang masuk berhasil ditambahkan.');
    }

    /**
     * Hapus data barang masuk (hanya Superadmin).
     */
    public function destroyBarangMasuk(ItemMovement $movement)
    {
        // Pastikan tipe-nya 'masuk'
        if ($movement->type !== 'masuk') {
            return redirect()->route('items.barang-masuk')
                ->with('error', 'Data yang dihapus bukan barang masuk.');
        }

        // Kembalikan stok barang
        $item = $movement->item;
        if ($item && $item->quantity >= $movement->quantity) {
            $item->decrement('quantity', $movement->quantity);
        }

        $movement->delete();

        return redirect()->route('items.barang-masuk')
            ->with('success', 'Data barang masuk berhasil dihapus.');
    }

    /**
     * Tampilkan data barang keluar.
     */
    public function barangKeluar()
    {
        return view('items.barang-keluar.barang-keluar');
    }
}

