<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::paginate(10);
        $totalSupplier = Supplier::count();
        $supplierAktif = Supplier::where('is_active', true)->count();
        $pengirimanBulanIni = \App\Models\Item::whereNotNull('supplier_id')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $updateTerakhir = Supplier::latest('updated_at')->first()?->updated_at->diffForHumans() ?? 'Belum ada';

        return view('data-master.dataSupplier', compact('suppliers', 'totalSupplier', 'supplierAktif', 'pengirimanBulanIni', 'updateTerakhir'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'pic' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');
        Supplier::create($validated);

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $supplier = Supplier::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'pic' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $supplier->update($validated);

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Supplier berhasil dihapus.']);
        }

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }
}
