<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JurusanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Jurusan::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('kode_jurusan', 'like', "%{$search}%")
                  ->orWhere('kepala_jurusan', 'like', "%{$search}%");
            });
        }

        $jurusans = $query->paginate(10)->withQueryString();
        return view('data-master.dataJurusan', compact('jurusans'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'kode_jurusan' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9]+-[A-Za-z0-9]+-[0-9]+$/'],
            'kepala_jurusan' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ], [
            'kode_jurusan.regex' => 'Format kode jurusan harus seperti contoh X-RPL-2.',
            'kode_jurusan.required' => 'Kode jurusan wajib diisi.'
        ]);
        
        $validated['is_active'] = $request->boolean('is_active');

        \App\Models\Jurusan::create($validated);

        return redirect()->route('jurusan.index')->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        $jurusan = \App\Models\Jurusan::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'kode_jurusan' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9]+-[A-Za-z0-9]+-[0-9]+$/'],
            'kepala_jurusan' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ], [
            'kode_jurusan.regex' => 'Format kode jurusan harus seperti contoh X-RPL-2.',
            'kode_jurusan.required' => 'Kode jurusan wajib diisi.'
        ]);
        
        $validated['is_active'] = $request->boolean('is_active');

        $jurusan->update($validated);

        return redirect()->route('jurusan.index')->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $jurusan = \App\Models\Jurusan::findOrFail($id);
        $jurusan->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Jurusan berhasil dihapus.']);
        }

        return redirect()->route('jurusan.index')->with('success', 'Jurusan berhasil dihapus.');
    }
}
