<?php

namespace App\Http\Controllers;

use App\Models\AsalBarang;
use Illuminate\Http\Request;

class AsalBarangController extends Controller
{
    public function index()
    {
        $asals = AsalBarang::paginate(10);
        return view('data-master.dataAsalbarang', compact('asals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:asal_barangs',
            'description' => 'nullable|string',
        ]);

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

        $asal->update($validated);

        return redirect()->route('asal.index')
            ->with('success', 'Asal barang berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $asal = AsalBarang::findOrFail($id);
        $asal->delete();

        return redirect()->route('asal.index')
            ->with('success', 'Asal barang berhasil dihapus.');
    }
}
