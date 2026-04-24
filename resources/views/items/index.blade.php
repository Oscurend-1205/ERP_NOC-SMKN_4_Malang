@extends('layouts.app')

@section('title', 'Barang Elektronik')

@section('content')
<div class="page-header">
    <div>
        <h2>Barang Elektronik</h2>
        <p>Kelola inventaris barang elektronik laboratorium</p>
    </div>
    <a href="{{ route('items.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Barang
    </a>
</div>

{{-- Filter Bar --}}
<form action="{{ route('items.index') }}" method="GET" class="filter-bar">
    <div class="search-input">
        <i class="bi bi-search"></i>
        <input type="text" name="search" class="form-control" placeholder="Cari nama, kode, merek..." value="{{ request('search') }}">
    </div>
    <select name="category_id" class="form-control">
        <option value="">Semua Kategori</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
    </select>
    <select name="location_id" class="form-control">
        <option value="">Semua Lokasi</option>
        @foreach($locations as $loc)
            <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
        @endforeach
    </select>
    <select name="condition" class="form-control">
        <option value="">Semua Kondisi</option>
        <option value="baik" {{ request('condition') == 'baik' ? 'selected' : '' }}>Baik</option>
        <option value="rusak_ringan" {{ request('condition') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
        <option value="rusak_berat" {{ request('condition') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
        <option value="hilang" {{ request('condition') == 'hilang' ? 'selected' : '' }}>Hilang</option>
    </select>
    <button type="submit" class="btn btn-secondary btn-sm">
        <i class="bi bi-funnel"></i> Filter
    </button>
    @if(request()->hasAny(['search', 'category_id', 'location_id', 'condition', 'status']))
        <a href="{{ route('items.index') }}" class="btn btn-secondary btn-sm">
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
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Merek</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th style="text-align: center;">Qty</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                        <th style="width: 140px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $i => $item)
                        <tr>
                            <td>{{ $items->firstItem() + $i }}</td>
                            <td><span class="badge badge-secondary" style="font-family: monospace;">{{ $item->code }}</span></td>
                            <td style="font-weight: 600;">{{ $item->name }}</td>
                            <td>{{ $item->brand ?? '-' }}</td>
                            <td>{{ $item->category->name }}</td>
                            <td>{{ $item->location->name }}</td>
                            <td style="text-align: center;">{{ $item->quantity }}</td>
                            <td>
                                @php
                                    $condBadge = match($item->condition) {
                                        'baik' => 'badge-success',
                                        'rusak_ringan' => 'badge-warning',
                                        'rusak_berat' => 'badge-danger',
                                        'hilang' => 'badge-secondary',
                                        default => 'badge-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $condBadge }}">{{ $item->condition_label }}</span>
                            </td>
                            <td>
                                @php
                                    $statusBadge = match($item->status) {
                                        'tersedia' => 'badge-success',
                                        'dipinjam' => 'badge-info',
                                        'maintenance' => 'badge-warning',
                                        'dimusnahkan' => 'badge-danger',
                                        default => 'badge-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $statusBadge }}">{{ $item->status_label }}</span>
                            </td>
                            <td style="text-align: center;">
                                <div class="flex items-center gap-2" style="justify-content: center;">
                                    <a href="{{ route('items.show', $item) }}" class="btn btn-secondary btn-icon" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('items.edit', $item) }}" class="btn btn-secondary btn-icon" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('items.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin hapus barang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-icon" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <i class="bi bi-cpu"></i>
                                    <p>Belum ada barang. Tambahkan barang pertama!</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($items->hasPages())
    <div class="pagination-wrapper">
        {{ $items->appends(request()->query())->links() }}
    </div>
@endif
@endsection
