@extends('layouts.app')

@section('title', 'Detail Barang')

@section('content')
<div class="page-header">
    <div>
        <h2>Detail Barang</h2>
        <p>{{ $item->name }} — {{ $item->code }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('items.edit', $item) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('items.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="grid-2">
    {{-- Info Utama --}}
    <div class="card">
        <div class="card-header">
            <h2><i class="bi bi-info-circle" style="margin-right: 8px; color: var(--primary);"></i> Informasi Barang</h2>
        </div>
        <div class="card-body">
            <div style="display: grid; gap: 16px;">
                @php
                    $details = [
                        ['label' => 'Kode Inventaris', 'value' => $item->code, 'badge' => true],
                        ['label' => 'Nama Barang', 'value' => $item->name],
                        ['label' => 'Serial Number', 'value' => $item->serial_number ?? '-'],
                        ['label' => 'Merek', 'value' => $item->brand ?? '-'],
                        ['label' => 'Model', 'value' => $item->model ?? '-'],
                        ['label' => 'Kategori', 'value' => $item->category->name],
                        ['label' => 'Lokasi', 'value' => $item->location->name],
                        ['label' => 'Jumlah', 'value' => $item->quantity . ' unit'],
                    ];
                @endphp
                @foreach($details as $d)
                    <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid var(--border-color);">
                        <span style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">{{ $d['label'] }}</span>
                        @if(isset($d['badge']) && $d['badge'])
                            <span class="badge badge-secondary" style="font-family: monospace;">{{ $d['value'] }}</span>
                        @else
                            <span style="font-size: 14px; font-weight: 600;">{{ $d['value'] }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Status & Harga --}}
    <div class="card">
        <div class="card-header">
            <h2><i class="bi bi-clipboard-data" style="margin-right: 8px; color: var(--info);"></i> Status & Nilai</h2>
        </div>
        <div class="card-body">
            <div style="display: grid; gap: 16px;">
                <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid var(--border-color);">
                    <span style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">Kondisi</span>
                    @php
                        $condBadge = match($item->condition) {
                            'baik' => 'badge-success',
                            'rusak_ringan' => 'badge-warning',
                            'rusak_berat' => 'badge-danger',
                            default => 'badge-secondary',
                        };
                    @endphp
                    <span class="badge {{ $condBadge }}">{{ $item->condition_label }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid var(--border-color);">
                    <span style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">Status</span>
                    @php
                        $statusBadge = match($item->status) {
                            'tersedia' => 'badge-success',
                            'dipinjam' => 'badge-info',
                            'maintenance' => 'badge-warning',
                            default => 'badge-danger',
                        };
                    @endphp
                    <span class="badge {{ $statusBadge }}">{{ $item->status_label }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid var(--border-color);">
                    <span style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">Tanggal Pembelian</span>
                    <span style="font-size: 14px; font-weight: 600;">{{ $item->purchase_date ? $item->purchase_date->format('d M Y') : '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid var(--border-color);">
                    <span style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">Harga Beli</span>
                    <span style="font-size: 14px; font-weight: 700; color: var(--primary);">
                        {{ $item->purchase_price ? 'Rp ' . number_format($item->purchase_price, 0, ',', '.') : '-' }}
                    </span>
                </div>
                @if($item->notes)
                <div>
                    <span style="font-size: 13px; color: var(--text-secondary); font-weight: 500; display: block; margin-bottom: 6px;">Catatan</span>
                    <div style="font-size: 14px; background: var(--bg-body); padding: 12px; border-radius: var(--radius-sm);">{{ $item->notes }}</div>
                </div>
                @endif
            </div>

            @if($item->image)
                <div style="margin-top: 20px;">
                    <span style="font-size: 13px; color: var(--text-secondary); font-weight: 500; display: block; margin-bottom: 8px;">Foto Barang</span>
                    <img src="{{ Storage::url($item->image) }}" alt="Foto {{ $item->name }}" style="max-width: 100%; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Riwayat Pergerakan --}}
<div class="card" style="margin-top: 20px;">
    <div class="card-header">
        <h2><i class="bi bi-clock-history" style="margin-right: 8px; color: var(--warning);"></i> Riwayat Pergerakan</h2>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Tipe</th>
                        <th>Dari</th>
                        <th>Ke</th>
                        <th>Qty</th>
                        <th>User</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($item->movements as $mv)
                        <tr>
                            <td>{{ $mv->movement_date->format('d M Y') }}</td>
                            <td>
                                @php
                                    $typeBadge = match($mv->type) {
                                        'masuk' => 'badge-success',
                                        'keluar' => 'badge-danger',
                                        'pindah' => 'badge-info',
                                        'maintenance' => 'badge-warning',
                                        default => 'badge-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $typeBadge }}">{{ $mv->type_label }}</span>
                            </td>
                            <td>{{ $mv->fromLocation->name ?? '-' }}</td>
                            <td>{{ $mv->toLocation->name ?? '-' }}</td>
                            <td>{{ $mv->quantity }}</td>
                            <td>{{ $mv->user->name ?? '-' }}</td>
                            <td class="text-muted">{{ $mv->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <p>Belum ada riwayat pergerakan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
