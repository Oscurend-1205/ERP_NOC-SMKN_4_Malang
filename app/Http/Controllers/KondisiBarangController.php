<?php

namespace App\Http\Controllers;

use App\Models\KondisiBarang;
use Illuminate\Http\Request;

class KondisiBarangController extends Controller
{
    public function index(Request $request)
    {
        $query = KondisiBarang::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $kondisis = $query->paginate(10)->withQueryString();
        return view('data-master.kondisiBarang', compact('kondisis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:kondisi_barangs',
            'label_color' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        KondisiBarang::create($validated);

        return redirect()->route('kondisi.index')
            ->with('success', 'Kondisi barang berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $kondisi = KondisiBarang::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:kondisi_barangs,name,' . $id,
            'label_color' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $kondisi->update($validated);

        return redirect()->route('kondisi.index')
            ->with('success', 'Kondisi barang berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $kondisi = KondisiBarang::findOrFail($id);
        $kondisi->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Kondisi barang berhasil dihapus.']);
        }

        return redirect()->route('kondisi.index')
            ->with('success', 'Kondisi barang berhasil dihapus.');
    }
}
