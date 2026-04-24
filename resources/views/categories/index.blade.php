@extends('layouts.app')

@section('title', 'Kategori Barang')

@section('content')
<div class="page-header">
    <div>
        <h2>Kategori Barang</h2>
        <p>Kelola kategori barang elektronik laboratorium</p>
    </div>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Kategori
    </a>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th style="text-align: center;">Jumlah Barang</th>
                        <th style="width: 120px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $i => $category)
                        <tr>
                            <td>{{ $categories->firstItem() + $i }}</td>
                            <td style="font-weight: 600;">{{ $category->name }}</td>
                            <td class="text-muted">{{ $category->description ?? '-' }}</td>
                            <td style="text-align: center;">
                                <span class="badge badge-info">{{ $category->items_count }} unit</span>
                            </td>
                            <td style="text-align: center;">
                                <div class="flex items-center gap-2" style="justify-content: center;">
                                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-secondary btn-icon" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Yakin hapus kategori ini?')">
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
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="bi bi-tags"></i>
                                    <p>Belum ada kategori. Tambahkan kategori pertama!</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($categories->hasPages())
    <div class="pagination-wrapper">
        {{ $categories->links() }}
    </div>
@endif
@endsection
