@extends('layouts.app')

@section('title', 'Tambah Lokasi')

@section('content')
<div class="page-header">
    <div>
        <h2>Tambah Lokasi</h2>
        <p>Tambahkan lokasi laboratorium atau gudang baru</p>
    </div>
    <a href="{{ route('locations.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('locations.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="name">Nama Lokasi <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Contoh: Lab Jaringan 1" required>
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="code">Kode Lokasi <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="code" id="code" class="form-control" value="{{ old('code') }}" placeholder="Contoh: LAB-01" required>
                    @error('code') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Deskripsi</label>
                <textarea name="description" id="description" class="form-control" placeholder="Keterangan lokasi (opsional)">{{ old('description') }}</textarea>
                @error('description') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Simpan
                </button>
                <a href="{{ route('locations.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
