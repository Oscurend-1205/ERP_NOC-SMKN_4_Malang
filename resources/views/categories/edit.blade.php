@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<div class="page-header">
    <div>
        <h2>Edit Kategori</h2>
        <p>Perbarui data kategori: {{ $category->name }}</p>
    </div>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label" for="name">Nama Kategori <span style="color: var(--danger);">*</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                @error('name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Deskripsi</label>
                <textarea name="description" id="description" class="form-control">{{ old('description', $category->description) }}</textarea>
                @error('description') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Perbarui
                </button>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
