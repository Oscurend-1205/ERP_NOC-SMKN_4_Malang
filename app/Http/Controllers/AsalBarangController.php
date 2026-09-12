<?php

namespace App\Http\Controllers;

use App\Models\AsalBarang;
use Illuminate\Http\Request;

class AsalBarangController extends Controller
{
    public function index(Request $request)
    {
        $query = AsalBarang::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $asals = $query->paginate(10)->withQueryString();
        return view('data-master.dataAsalbarang', compact('asals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:asal_barangs',
            'description' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        AsalBarang::create($validated);

        return redirect()->route('asal.index')
            ->with('success', 'Asal barang berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $asal = AsalBarang::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:asal_barangs,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $asal->update($validated);

        return redirect()->route('asal.index')
            ->with('success', 'Asal barang berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $asal = AsalBarang::findOrFail($id);
        $asal->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Asal barang berhasil dihapus.']);
        }

        return redirect()->route('asal.index')
            ->with('success', 'Asal barang berhasil dihapus.');
    }
}
