@extends('layouts.app')

@section('title', 'Detail Stok Opname - ' . $stockTake->code)

@section('content')
<div class="mb-6">
    <a href="{{ route('stock-take.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-[#3F51B5] transition-colors mb-3">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
        Kembali ke Stok Opname
    </a>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ $stockTake->code }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $stockTake->title }}</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            @if(in_array($stockTake->status, ['draft', 'in_progress']))
                <form method="POST" action="{{ route('stock-take.complete', $stockTake->id) }}" onsubmit="return confirm('Selesaikan sesi stok opname ini?')">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-yellow-500 text-white text-sm font-semibold rounded-xl hover:bg-yellow-600 transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                        Selesaikan Opname
                    </button>
                </form>
            @endif
            @if($stockTake->status === 'completed' && auth()->user()->isSuperadmin())
                <form method="POST" action="{{ route('stock-take.approve', $stockTake->id) }}" onsubmit="return confirm('Setujui hasil stok opname ini?')">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">verified</span>
                        Approve
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
<div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700 flex items-center gap-2">
    <span class="material-symbols-outlined text-[20px]">check_circle</span>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 flex items-center gap-2">
    <span class="material-symbols-outlined text-[20px]">error</span>
    {{ session('error') }}
</div>
@endif

<!-- Info Cards -->
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <!-- Status -->
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Status</div>
        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold {{ $stockTake->status_color }}">{{ $stockTake->status_label }}</span>
    </div>
    <!-- Progress -->
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Progress</div>
        <div class="text-xl font-extrabold text-gray-800">{{ $stats['progress'] }}%</div>
        <div class="text-xs text-gray-400">{{ $stats['checked'] }}/{{ $stats['total'] }} item</div>
    </div>
    <!-- Cocok -->
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Cocok</div>
        <div class="text-xl font-extrabold text-green-600">{{ $stats['match'] }}</div>
    </div>
    <!-- Kurang -->
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Kurang</div>
        <div class="text-xl font-extrabold text-red-600">{{ $stats['short'] }}</div>
    </div>
    <!-- Lebih -->
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Lebih</div>
        <div class="text-xl font-extrabold text-blue-600">{{ $stats['over'] }}</div>
    </div>
</div>

<!-- Progress Bar -->
<div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm mb-6">
    <div class="flex items-center justify-between mb-2">
        <span class="text-sm font-semibold text-gray-700">Progress Pengecekan</span>
        <span class="text-sm font-bold text-gray-800">{{ $stats['progress'] }}%</span>
    </div>
    <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
        <div class="h-full rounded-full transition-all duration-500 {{ $stats['progress'] === 100 ? 'bg-green-500' : 'bg-[#3F51B5]' }}" style="width: {{ $stats['progress'] }}%"></div>
    </div>
</div>

<!-- Info Sesi -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
        <div>
            <span class="text-gray-400 text-xs font-semibold uppercase">Lokasi</span>
            <div class="font-semibold text-gray-800 mt-0.5">{{ $stockTake->location->name ?? 'Semua Lokasi' }}</div>
        </div>
        <div>
            <span class="text-gray-400 text-xs font-semibold uppercase">Petugas</span>
            <div class="font-semibold text-gray-800 mt-0.5">{{ $stockTake->startedByUser->name ?? '-' }}</div>
        </div>
        <div>
            <span class="text-gray-400 text-xs font-semibold uppercase">Tanggal Mulai</span>
            <div class="font-semibold text-gray-800 mt-0.5">{{ $stockTake->started_at ? $stockTake->started_at->format('d/m/Y H:i') : '-' }}</div>
        </div>
        <div>
            <span class="text-gray-400 text-xs font-semibold uppercase">Disetujui oleh</span>
            <div class="font-semibold text-gray-800 mt-0.5">{{ $stockTake->approvedByUser->name ?? '-' }}</div>
        </div>
    </div>
    @if($stockTake->notes_summary)
    <div class="mt-4 pt-4 border-t border-gray-100">
        <span class="text-gray-400 text-xs font-semibold uppercase">Ringkasan</span>
        <div class="text-sm text-gray-700 mt-0.5">{{ $stockTake->notes_summary }}</div>
    </div>
    @endif
</div>

<!-- Filter & Search -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-4">
    <form method="GET" action="{{ route('stock-take.show', $stockTake->id) }}" class="flex flex-col sm:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/kode barang..."
            class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
        <select name="filter" class="px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">Semua Item</option>
            <option value="unchecked" {{ request('filter') === 'unchecked' ? 'selected' : '' }}>Belum Dicek</option>
            <option value="match" {{ request('filter') === 'match' ? 'selected' : '' }}>Cocok</option>
            <option value="discrepancy" {{ request('filter') === 'discrepancy' ? 'selected' : '' }}>Ada Selisih</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-[#3F51B5] text-white text-sm font-semibold rounded-lg hover:bg-[#3949AB] transition-colors">Filter</button>
    </form>
</div>

<!-- Items Table -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kode</th>
                    <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Barang</th>
                    <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Qty Sistem</th>
                    <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center" style="min-width:120px">Qty Aktual</th>
                    <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center" style="min-width:140px">Kondisi Aktual</th>
                    <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Selisih</th>
                    <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider" style="min-width:140px">Catatan</th>
                    <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($stockTakeItems as $sti)
                <tr class="hover:bg-gray-50 transition-colors" id="row-{{ $sti->item_id }}">
                    <td class="py-3 px-4 text-sm font-mono text-[#3F51B5] font-semibold">{{ $sti->item->code ?? '-' }}</td>
                    <td class="py-3 px-4">
                        <div class="text-sm font-semibold text-gray-800">{{ $sti->item->name ?? '-' }}</div>
                        <div class="text-xs text-gray-400">{{ $sti->item->category->name ?? '-' }}</div>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <span class="text-sm font-bold text-gray-800">{{ $sti->system_quantity }}</span>
                        <div class="text-xs text-gray-400">{{ $sti->system_condition }}</div>
                    </td>
                    <td class="py-3 px-4 text-center">
                        @if(in_array($stockTake->status, ['draft', 'in_progress']))
                        <input type="number" min="0"
                            id="actual-{{ $sti->item_id }}"
                            value="{{ $sti->actual_quantity }}"
                            placeholder="—"
                            class="w-20 px-2 py-1.5 border border-gray-200 rounded-lg text-sm text-center focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none mx-auto block">
                        @else
                        <span class="text-sm font-bold text-gray-800">{{ $sti->actual_quantity ?? '—' }}</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-center">
                        @if(in_array($stockTake->status, ['draft', 'in_progress']))
                        <select id="condition-{{ $sti->item_id }}" class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-xs bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">Pilih</option>
                            <option value="baik" {{ $sti->actual_condition === 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak_ringan" {{ $sti->actual_condition === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="rusak_berat" {{ $sti->actual_condition === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                            <option value="hilang" {{ $sti->actual_condition === 'hilang' ? 'selected' : '' }}>Hilang</option>
                        </select>
                        @else
                        <span class="text-xs">{{ ucfirst(str_replace('_', ' ', $sti->actual_condition ?? '—')) }}</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-center">
                        <span id="diff-{{ $sti->item_id }}" class="text-sm font-bold {{ $sti->difference_color }}">
                            {{ $sti->is_checked ? ($sti->difference >= 0 ? '+' : '') . $sti->difference : '—' }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        @if(in_array($stockTake->status, ['draft', 'in_progress']))
                        <input type="text" id="notes-{{ $sti->item_id }}" value="{{ $sti->notes }}" placeholder="Catatan..."
                            class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-xs focus:ring-2 focus:ring-blue-500 outline-none">
                        @else
                        <span class="text-xs text-gray-600">{{ $sti->notes ?? '-' }}</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-center">
                        @if(in_array($stockTake->status, ['draft', 'in_progress']))
                        <button onclick="saveItem({{ $stockTake->id }}, {{ $sti->item_id }})" class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Simpan" id="btn-{{ $sti->item_id }}">
                            <span class="material-symbols-outlined text-[20px]">save</span>
                        </button>
                        @else
                            @if($sti->is_checked)
                            <span class="material-symbols-outlined text-[20px] text-green-500">check_circle</span>
                            @else
                            <span class="material-symbols-outlined text-[20px] text-gray-300">radio_button_unchecked</span>
                            @endif
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-12 text-center text-gray-400">
                        <span class="material-symbols-outlined text-[48px] mb-2 opacity-20">inventory</span>
                        <p class="text-sm font-medium">Tidak ada item yang cocok dengan filter</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($stockTakeItems->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $stockTakeItems->links() }}
    </div>
    @endif
</div>

@if(in_array($stockTake->status, ['draft', 'in_progress']))
<script>
function saveItem(stockTakeId, itemId) {
    const actualQty = document.getElementById('actual-' + itemId)?.value;
    const actualCondition = document.getElementById('condition-' + itemId)?.value;
    const notes = document.getElementById('notes-' + itemId)?.value;
    const btn = document.getElementById('btn-' + itemId);
    const diffEl = document.getElementById('diff-' + itemId);

    if (actualQty === '' || actualQty === null) {
        alert('Masukkan jumlah aktual terlebih dahulu.');
        return;
    }

    // Show loading state
    btn.innerHTML = '<span class="material-symbols-outlined text-[20px] animate-spin">autorenew</span>';
    btn.disabled = true;

    fetch(`/stock-take/${stockTakeId}/item/${itemId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            actual_quantity: parseInt(actualQty),
            actual_condition: actualCondition,
            notes: notes
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Update difference display
            const diff = data.difference;
            diffEl.textContent = (diff >= 0 ? '+' : '') + diff;
            diffEl.className = 'text-sm font-bold ' + data.difference_color;

            // Show success state
            btn.innerHTML = '<span class="material-symbols-outlined text-[20px] text-green-600">check</span>';
            setTimeout(() => {
                btn.innerHTML = '<span class="material-symbols-outlined text-[20px]">save</span>';
                btn.disabled = false;
            }, 1500);

            // Highlight row briefly
            const row = document.getElementById('row-' + itemId);
            row.classList.add('bg-green-50');
            setTimeout(() => row.classList.remove('bg-green-50'), 1000);
        } else {
            alert(data.error || 'Gagal menyimpan.');
            btn.innerHTML = '<span class="material-symbols-outlined text-[20px]">save</span>';
            btn.disabled = false;
        }
    })
    .catch(err => {
        alert('Terjadi kesalahan jaringan.');
        btn.innerHTML = '<span class="material-symbols-outlined text-[20px]">save</span>';
        btn.disabled = false;
    });
}
</script>
@endif
@endsection
