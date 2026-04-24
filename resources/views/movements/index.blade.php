@extends('layouts.app')

@section('title', 'Mutasi Barang')

@section('content')
<div class="page-header">
    <div>
        <h2>Mutasi Barang</h2>
        <p>Riwayat pergerakan dan mutasi barang elektronik</p>
    </div>
    <a href="{{ route('movements.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Catat Mutasi
    </a>
</div>

{{-- Filter --}}
<form action="{{ route('movements.index') }}" method="GET" class="filter-bar">
    <select name="type" class="form-control">
        <option value="">Semua Tipe</option>
        <option value="masuk" {{ request('type') == 'masuk' ? 'selected' : '' }}>Barang Masuk</option>
        <option value="keluar" {{ request('type') == 'keluar' ? 'selected' : '' }}>Barang Keluar</option>
        <option value="pindah" {{ request('type') == 'pindah' ? 'selected' : '' }}>Pindah Lokasi</option>
        <option value="maintenance" {{ request('type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
        <option value="rusak" {{ request('type') == 'rusak' ? 'selected' : '' }}>Rusak</option>
        <option value="musnahkan" {{ request('type') == 'musnahkan' ? 'selected' : '' }}>Dimusnahkan</option>
    </select>
    <button type="submit" class="btn btn-secondary btn-sm">
        <i class="bi bi-funnel"></i> Filter
    </button>
    @if(request()->hasAny(['type', 'item_id']))
        <a href="{{ route('movements.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-x-lg"></i> Reset
        </a>
    @endif
</form>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Tanggal</th>
                        <th>Barang</th>
                        <th>Tipe</th>
                        <th>Dari</th>
                        <th>Ke</th>
                        <th style="text-align: center;">Qty</th>
                        <th>User</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $i => $mv)
                        <tr>
                            <td>{{ $movements->firstItem() + $i }}</td>
                            <td>{{ $mv->movement_date->format('d M Y') }}</td>
                            <td style="font-weight: 600;">{{ $mv->item->name ?? '-' }}</td>
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
                            <td style="text-align: center;">{{ $mv->quantity }}</td>
                            <td>{{ $mv->user->name ?? '-' }}</td>
                            <td class="text-muted" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $mv->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="bi bi-arrow-left-right"></i>
                                    <p>Belum ada riwayat mutasi barang</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($movements->hasPages())
    <div class="pagination-wrapper">
        {{ $movements->appends(request()->query())->links() }}
    </div>
@endif
@endsection
