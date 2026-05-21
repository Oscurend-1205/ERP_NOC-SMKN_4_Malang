@extends('layouts.app')

@section('title', 'Catat Mutasi')

@section('content')
<div class="page-header">
    <div>
        <h2>Catat Mutasi Barang</h2>
        <p>Catat pergerakan masuk, keluar, atau pindah barang</p>
    </div>
    <a href="{{ route('movements.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('movements.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="item_id">Pilih Barang <span style="color: var(--danger);">*</span></label>
                    <select name="item_id" id="item_id" class="form-control" required>
                        <option value="">Pilih Barang</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->code }} - {{ $item->name }} ({{ $item->location->name ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    @error('item_id') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="type">Tipe Mutasi <span style="color: var(--danger);">*</span></label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="">Pilih Tipe</option>
                        <option value="masuk" {{ old('type') == 'masuk' ? 'selected' : '' }}>Barang Masuk</option>
                        <option value="keluar" {{ old('type') == 'keluar' ? 'selected' : '' }}>Barang Keluar</option>
                        <option value="pindah" {{ old('type') == 'pindah' ? 'selected' : '' }}>Pindah Lokasi</option>
                        <option value="maintenance" {{ old('type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="rusak" {{ old('type') == 'rusak' ? 'selected' : '' }}>Lapor Rusak</option>
                        <option value="musnahkan" {{ old('type') == 'musnahkan' ? 'selected' : '' }}>Musnahkan</option>
                    </select>
                    @error('type') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="from_location_id">Dari Lokasi</label>
                    <select name="from_location_id" id="from_location_id" class="form-control">
                        <option value="">- Tidak ada -</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ old('from_location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                        @endforeach
                    </select>
                    @error('from_location_id') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="to_location_id">Ke Lokasi</label>
                    <select name="to_location_id" id="to_location_id" class="form-control">
                        <option value="">- Tidak ada -</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ old('to_location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                        @endforeach
                    </select>
                    @error('to_location_id') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="quantity">Jumlah <span style="color: var(--danger);">*</span></label>
                    <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity', 1) }}" min="1" required>
                    @error('quantity') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="movement_date">Tanggal Mutasi <span style="color: var(--danger);">*</span></label>
                    <input type="date" name="movement_date" id="movement_date" class="form-control" value="{{ old('movement_date', date('Y-m-d')) }}" required>
                    @error('movement_date') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="notes">Catatan</label>
                <textarea name="notes" id="notes" class="form-control" placeholder="Keterangan mutasi (opsional)">{{ old('notes') }}</textarea>
                @error('notes') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Simpan Mutasi
                </button>
                <a href="{{ route('movements.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
