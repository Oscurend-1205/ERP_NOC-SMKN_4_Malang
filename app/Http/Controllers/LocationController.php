<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Tampilkan semua lokasi.
     */
    public function index(Request $request)
    {
        $query = Location::withCount('items');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('penanggung_jawab', 'like', "%{$search}%");
            });
        }

        $locations = $query->paginate(10)->withQueryString();
        return view('data-master.dataRuangan', compact('locations'));
    }

    /**
     * Tampilkan form tambah lokasi.
     */
    public function create()
    {
        return view('locations.create');
    }

    /**
     * Simpan lokasi baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:locations',
            'penanggung_jawab' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        Location::create($validated);

        return redirect()->route('locations.index')
            ->with('success', 'Lokasi berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit lokasi.
     */
    public function edit(Location $location)
    {
        return view('locations.edit', compact('location'));
    }

    /**
     * Update lokasi.
     */
    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:locations,code,' . $location->id,
            'penanggung_jawab' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $location->update($validated);

        return redirect()->route('locations.index')
            ->with('success', 'Lokasi berhasil diperbarui.');
    }

    /**
     * Hapus lokasi.
     */
    public function destroy(Location $location)
    {
        $location->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Lokasi berhasil dihapus.']);
        }

        return redirect()->route('locations.index')
            ->with('success', 'Lokasi berhasil dihapus.');
    }
}
