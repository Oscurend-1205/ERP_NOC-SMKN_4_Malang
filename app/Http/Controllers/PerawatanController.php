<?php

namespace App\Http\Controllers;

use App\Models\Perawatan;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerawatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Perawatan::with(['item', 'user']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('jenis_perawatan', 'like', "%{$search}%")
                  ->orWhere('catatan', 'like', "%{$search}%")
                  ->orWhereHas('item', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('code', 'like', "%{$search}%");
                  });
            });
        }

        $perawatans = $query->latest()->paginate(10)->withQueryString();
        
        $totalPerawatan = Perawatan::count();
        $menungguPersetujuan = Perawatan::where('status', 'menunggu')->count();
        $sedangBerlangsung = Perawatan::where('status', 'proses')->count();
        $selesai = Perawatan::where('status', 'selesai')->count();

        // Ambil data aset/barang untuk opsi dropdown (kecuali yang sudah dimusnahkan)
        $items = Item::select('id', 'name', 'code', 'brand', 'model')
            ->where('status', '!=', 'dimusnahkan')
            ->orderBy('name')
            ->get();

        return view('data-perawatan.dataPerawatan', compact(
            'perawatans', 
            'totalPerawatan', 
            'menungguPersetujuan', 
            'sedangBerlangsung',
            'selesai',
            'items'
        ));
    }

    public function create()
    {
        return redirect()->route('perawatan.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'jenis_perawatan' => 'required|string|max:255',
            'tanggal_pengajuan' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'menunggu';

        Perawatan::create($validated);

        return redirect()->route('perawatan.index')->with('success', 'Pengajuan perawatan berhasil ditambahkan.');
    }

    public function show(Perawatan $perawatan)
    {
        $perawatan->load(['item.category', 'user']);
        return response()->json($perawatan);
    }

    public function edit(Perawatan $perawatan)
    {
        return redirect()->route('perawatan.index');
    }

    /**
     * Update status dan data perawatan.
     * Transisi status: menunggu -> proses -> selesai
     */
    public function update(Request $request, Perawatan $perawatan)
    {
        $validated = $request->validate([
            'status' => 'required|in:menunggu,proses,selesai',
            'jenis_perawatan' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'tanggal_selesai' => 'nullable|date',
        ]);

        // Jika status berubah ke 'selesai', otomatis set tanggal_selesai
        if ($validated['status'] === 'selesai' && empty($validated['tanggal_selesai'])) {
            $validated['tanggal_selesai'] = now()->toDateString();
        }

        // Jika status berubah ke 'selesai', update status item ke 'tersedia'
        if ($validated['status'] === 'selesai' && $perawatan->status !== 'selesai') {
            $item = $perawatan->item;
            if ($item && $item->status === 'maintenance') {
                $item->update(['status' => 'tersedia']);
            }
        }

        // Jika status berubah ke 'proses', update status item ke 'maintenance'
        if ($validated['status'] === 'proses' && $perawatan->status === 'menunggu') {
            $item = $perawatan->item;
            if ($item) {
                $item->update(['status' => 'maintenance']);
            }
        }

        $perawatan->update($validated);

        return redirect()->route('perawatan.index')->with('success', 'Data perawatan berhasil diperbarui.');
    }

    /**
     * Hapus data perawatan (Superadmin only).
     */
    public function destroy(Perawatan $perawatan)
    {
        if (Auth::user()->role !== 'Superadmin') {
            return redirect()->route('perawatan.index')->with('error', 'Hanya Superadmin yang dapat menghapus data perawatan.');
        }

        // Jika perawatan masih 'proses', kembalikan status item
        if ($perawatan->status === 'proses') {
            $item = $perawatan->item;
            if ($item && $item->status === 'maintenance') {
                $item->update(['status' => 'tersedia']);
            }
        }

        $perawatan->delete();

        return redirect()->route('perawatan.index')->with('success', 'Data perawatan berhasil dihapus.');
    }
}
