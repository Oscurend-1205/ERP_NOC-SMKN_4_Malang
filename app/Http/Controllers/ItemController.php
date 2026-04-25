<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('created_at', 'desc')->paginate(15);
        $categories = Category::all();
        $locations = Location::all();

        return view('items.index', compact('items', 'categories', 'locations'));
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
            'code' => 'required|string|max:100|unique:items',
            'name' => 'required|string|max:255',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'category_id' => 'required|exists:categories,id',
            'purchase_date' => 'required|date',
            // Field opsional atau yang bisa diset default jika dikirim dari form scan
            'location_id' => 'required|exists:locations,id',
            'quantity' => 'nullable|integer|min:1',
            'status' => 'nullable|in:tersedia,dipinjam,maintenance,dimusnahkan',
        ]);

        // Beri default value untuk field yang tidak ada di form scanner
        $validated['quantity'] = $validated['quantity'] ?? 1;
        $validated['status'] = $validated['status'] ?? 'tersedia';

        Item::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil disimpan!',
        ]);
    }

    /**
     * Simpan barang baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:items',
            'serial_number' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'status' => 'required|in:tersedia,dipinjam,maintenance,dimusnahkan',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        Item::create($validated);

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil ditambahkan.');
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
            'purchase_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

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
}
