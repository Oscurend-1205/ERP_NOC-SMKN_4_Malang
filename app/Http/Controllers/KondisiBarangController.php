<?php

namespace App\Http\Controllers;

use App\Models\KondisiBarang;
use Illuminate\Http\Request;

class KondisiBarangController extends Controller
{
    public function index()
    {
        $kondisis = KondisiBarang::paginate(10);
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

        return redirect()->route('kondisi.index')
            ->with('success', 'Kondisi barang berhasil dihapus.');
    }
}
