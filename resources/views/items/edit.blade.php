@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
<div class="page-header">
    <div>
        <h2>Edit Barang</h2>
        <p>Perbarui data: {{ $item->name }} ({{ $item->code }})</p>
    </div>
    <a href="{{ route('items.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('items.update', $item) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="name">Nama Barang <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $item->name) }}" required>
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="code">Kode Inventaris <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $item->code) }}" required>
                    @error('code') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="serial_number">Serial Number</label>
                    <input type="text" name="serial_number" id="serial_number" class="form-control" value="{{ old('serial_number', $item->serial_number) }}">
                    @error('serial_number') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="brand">Merek</label>
                    <input type="text" name="brand" id="brand" class="form-control" value="{{ old('brand', $item->brand) }}">
                    @error('brand') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="model">Model</label>
                    <input type="text" name="model" id="model" class="form-control" value="{{ old('model', $item->model) }}">
                    @error('model') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="category_id">Kategori <span style="color: var(--danger);">*</span></label>
                    <select name="category_id" id="category_id" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $item->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="location_id">Lokasi <span style="color: var(--danger);">*</span></label>
                    <select name="location_id" id="location_id" class="form-control" required>
                        <option value="">Pilih Lokasi</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ old('location_id', $item->location_id) == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                        @endforeach
                    </select>
                    @error('location_id') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="quantity">Jumlah <span style="color: var(--danger);">*</span></label>
                    <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity', $item->quantity) }}" min="1" required>
                    @error('quantity') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="condition">Kondisi <span style="color: var(--danger);">*</span></label>
                    <select name="condition" id="condition" class="form-control" required>
                        <option value="baik" {{ old('condition', $item->condition) == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak_ringan" {{ old('condition', $item->condition) == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="rusak_berat" {{ old('condition', $item->condition) == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                        <option value="hilang" {{ old('condition', $item->condition) == 'hilang' ? 'selected' : '' }}>Hilang</option>
                    </select>
                    @error('condition') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="status">Status <span style="color: var(--danger);">*</span></label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="tersedia" {{ old('status', $item->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="dipinjam" {{ old('status', $item->status) == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="maintenance" {{ old('status', $item->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="dimusnahkan" {{ old('status', $item->status) == 'dimusnahkan' ? 'selected' : '' }}>Dimusnahkan</option>
                    </select>
                    @error('status') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="purchase_date">Tanggal Pembelian</label>
                    <input type="date" name="purchase_date" id="purchase_date" class="form-control" value="{{ old('purchase_date', $item->purchase_date?->format('Y-m-d')) }}">
                    @error('purchase_date') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="purchase_price">Harga Beli (Rp)</label>
                    <input type="number" name="purchase_price" id="purchase_price" class="form-control" value="{{ old('purchase_price', $item->purchase_price) }}" min="0">
                    @error('purchase_price') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="notes">Catatan</label>
                <textarea name="notes" id="notes" class="form-control">{{ old('notes', $item->notes) }}</textarea>
                @error('notes') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="image">Foto Barang</label>
                @if($item->image)
                    <div style="margin-bottom: 8px;">
                        <img src="{{ Storage::url($item->image) }}" alt="Foto {{ $item->name }}" style="max-width: 200px; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                    </div>
                @endif
                <input type="file" name="image" id="image" class="form-control" accept="image/jpeg,image/png,image/jpg">
                <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Kosongkan jika tidak ingin mengganti foto</div>
                @error('image') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Perbarui
                </button>
                <a href="{{ route('items.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
