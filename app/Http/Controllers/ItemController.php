<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemMovement;
use App\Models\Location;
use App\Models\Peminjaman;
use App\Models\Supplier;
use App\Models\AsalBarang;
use App\Models\KondisiBarang;
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

        // Kelompokkan barang berdasarkan identitas unik: name + brand + model + category + sub_prefix
        // Format kode: PREFIX-NUMBER atau PREFIX-SUBPREFIX-NUMBER
        // COUNT(*) = jumlah unit fisik nyata di database (1 row = 1 unit)
        // Condition & status TIDAK mempengaruhi pengelompokan — semua unit dengan identitas sama = 1 baris
        $items = $query->select(
                'name', 
                'brand', 
                'model', 
                'category_id', 
                'sub_prefix',
                \DB::raw("CASE WHEN sub_prefix IS NOT NULL AND sub_prefix != '' THEN CONCAT(SUBSTRING_INDEX(MAX(code), '-', 1), '-', sub_prefix) ELSE SUBSTRING_INDEX(MAX(code), '-', 1) END as prefix"),
                \DB::raw('COUNT(*) as total_stock'),
                \DB::raw('MIN(id) as id')
            )
            ->groupBy('name', 'brand', 'model', 'category_id', 'sub_prefix')
            ->orderBy('name', 'asc')
            ->paginate(15);

        $categories = Category::all();
        $locations = Location::all();
        $suppliers = Supplier::all();
        $asalBarangs = AsalBarang::all();
        $kondisis = KondisiBarang::all();

        // Ambil daftar barang unik berdasarkan nama, merk, model, dan sub_prefix untuk dropdown "Barang Sudah Ada"
        $existingItems = Item::select('name', 'brand', 'model', 'category_id', 'sub_prefix')
            ->groupBy('name', 'brand', 'model', 'category_id', 'sub_prefix')
            ->orderBy('name', 'asc')
            ->get();

        return view('items.index', compact('items', 'categories', 'locations', 'suppliers', 'asalBarangs', 'kondisis', 'existingItems'));
    }

    /**
     * AJAX endpoint to get specific units of a grouped item.
     * Filter by identity: name + brand + model + category_id + sub_prefix
     */
    public function units(Request $request)
    {
        $query = Item::with('location')
            ->where('name', $request->name)
            ->where('category_id', $request->category_id);

        // NULL-safe brand matching
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        } else {
            $query->where(function ($q) {
                $q->whereNull('brand')->orWhere('brand', '');
            });
        }

        // NULL-safe model matching
        if ($request->filled('model')) {
            $query->where('model', $request->model);
        } else {
            $query->where(function ($q) {
                $q->whereNull('model')->orWhere('model', '');
            });
        }

        // Filter by sub_prefix if provided (can be empty string for items without sub_prefix)
        if ($request->filled('sub_prefix')) {
            $query->where('sub_prefix', $request->sub_prefix);
        } else {
            $query->where(function ($q) {
                $q->whereNull('sub_prefix')->orWhere('sub_prefix', '');
            });
        }

        $units = $query->orderBy('code', 'asc')->get();

        return response()->json($units);
    }

    /**
     * Tampilkan form tambah barang.
     */
    public function create()
    {
        $categories = Category::all();
        $locations = Location::all();
        $suppliers = Supplier::all();
        $asalBarangs = AsalBarang::all();
        $kondisis = KondisiBarang::all();
        return view('items.create', compact('categories', 'locations', 'suppliers', 'asalBarangs', 'kondisis'));
    }

    /**
     * Generate unique item code based on category prefix.
     * Format: <PREFIX>-<NOMOR> (e.g., PRF-0001) atau <PREFIX>-<SUBPREFIX>-<NOMOR> (e.g., RTR-MKT-0001)
     * Uses last_code_number from categories table for atomic sequence.
     */
    private function generateItemCode($categoryId, $subPrefix = null)
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

        $code = $category->prefix;
        if (!empty($subPrefix)) {
            $code .= '-' . strtoupper(trim($subPrefix));
        }
        $code .= '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        return $code;
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
            'supplier_id' => 'nullable|exists:suppliers,id',
            'asal_barang_id' => 'nullable|exists:asal_barangs,id',
            'kondisi_barang_id' => 'nullable|exists:kondisi_barangs,id',
            'quantity' => 'required|integer|min:1',
            'condition' => 'nullable|in:baik,rusak_ringan,rusak_berat,hilang',
            'status' => 'required|in:tersedia,dipinjam,maintenance,dimusnahkan',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|string',
            'notes' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sub_prefix' => 'nullable|string|max:10',
        ]);

        // Clean purchase_price from dots if present
        if (isset($validated['purchase_price'])) {
            $validated['purchase_price'] = str_replace('.', '', $validated['purchase_price']);
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        $quantity = $validated['quantity'];
        
        // Ambil sub_prefix (opsional) dan normalisasi ke uppercase
        $subPrefix = !empty($validated['sub_prefix']) ? strtoupper(trim($validated['sub_prefix'])) : null;
        
        // Untuk setiap unit, kita buat record tersendiri dengan kode unik
        // Pastikan quantity untuk tiap record adalah 1
        $validated['quantity'] = 1;
        $validated['sub_prefix'] = $subPrefix;
        
        for ($i = 0; $i < $quantity; $i++) {
            $validated['code'] = $this->generateItemCode($validated['category_id'], $subPrefix);
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
        $suppliers = Supplier::all();
        $asalBarangs = AsalBarang::all();
        $kondisis = KondisiBarang::all();
        return view('items.edit', compact('item', 'categories', 'locations', 'suppliers', 'asalBarangs', 'kondisis'));
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
            'supplier_id' => 'nullable|exists:suppliers,id',
            'asal_barang_id' => 'nullable|exists:asal_barangs,id',
            'kondisi_barang_id' => 'nullable|exists:kondisi_barangs,id',
            'quantity' => 'required|integer|min:1',
            'condition' => 'nullable|in:baik,rusak_ringan,rusak_berat,hilang',
            'status' => 'required|in:tersedia,dipinjam,maintenance,dimusnahkan',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|string',
            'notes' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sub_prefix' => 'nullable|string|max:10',
        ]);

        // Clean purchase_price from dots if present
        if (isset($validated['purchase_price'])) {
            $validated['purchase_price'] = str_replace('.', '', $validated['purchase_price']);
        }

        // Normalize sub_prefix to uppercase
        if (!empty($validated['sub_prefix'])) {
            $validated['sub_prefix'] = strtoupper(trim($validated['sub_prefix']));
        } else {
            $validated['sub_prefix'] = null;
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
     * AJAX: Get next code preview for a category (with optional sub_prefix).
     */
    public function getNextCode(Request $request)
    {
        $categoryId = $request->query('category_id');
        $subPrefix  = $request->query('sub_prefix');
        if (!$categoryId) {
            return response()->json(['code' => null, 'prefix' => null, 'next_number' => null]);
        }

        $category = Category::find($categoryId);
        if (!$category || empty($category->prefix)) {
            return response()->json(['code' => null, 'prefix' => null, 'next_number' => null]);
        }

        $nextNumber = $category->last_code_number + 1;
        $code = $category->prefix;
        if (!empty($subPrefix)) {
            $code .= '-' . strtoupper(trim($subPrefix));
        }
        $code .= '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return response()->json([
            'code' => $code,
            'prefix' => $category->prefix,
            'next_number' => $nextNumber,
        ]);
    }

    /**
     * AJAX: Quick-create a new category with prefix (used from Add Item modal).
     */
    public function quickStoreCategory(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'prefix' => 'required|string|max:10|unique:categories,prefix',
        ]);

        $category = Category::create([
            'name'             => $validated['name'],
            'slug'             => \Illuminate\Support\Str::slug($validated['name']),
            'prefix'           => strtoupper(trim($validated['prefix'])),
            'last_code_number' => 0,
            'description'      => null,
        ]);

        return response()->json([
            'success' => true,
            'category' => [
                'id'     => $category->id,
                'name'   => $category->name,
                'prefix' => $category->prefix,
            ],
        ]);
    }

    /**
     * Tampilkan data barang keluar (riwayat peminjaman & pengeluaran).
     */
    public function barangKeluar(Request $request)
    {
        $query = Peminjaman::with('item.category');

        // Filter rentang tanggal
        if ($request->filled('date_range')) {
            $today = now()->toDateString();
            match ($request->date_range) {
                'today' => $query->whereDate('waktu_pinjam', $today),
                'week'  => $query->whereBetween('waktu_pinjam', [now()->startOfWeek(), now()->endOfWeek()]),
                'month' => $query->whereMonth('waktu_pinjam', now()->month)
                                   ->whereYear('waktu_pinjam', now()->year),
                default => null,
            };
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_peminjam', 'like', "%{$search}%")
                  ->orWhere('item_code', 'like', "%{$search}%")
                  ->orWhereHas('item', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $peminjamans = $query->orderBy('waktu_pinjam', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('items.barang-keluar.barang-keluar', compact('peminjamans'));
    }
}

