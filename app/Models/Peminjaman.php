<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'nama_peminjam',
        'kelas',
        'item_id',
        'item_code',
        'session_token',
        'waktu_pinjam',
        'waktu_kembali',
        'status',
        'catatan',
    ];

    protected $casts = [
        'waktu_pinjam' => 'datetime',
        'waktu_kembali' => 'datetime',
    ];

    /**
     * Relasi: Peminjaman terhubung ke satu barang.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Relasi: Peminjaman terhubung ke satu sesi scan.
     */
    public function scanSession(): BelongsTo
    {
        return $this->belongsTo(ScanSession::class, 'session_token', 'token');
    }

    /**
     * Label status peminjaman (untuk tampilan).
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'dipinjam' => 'Dipinjam',
            'dikembalikan' => 'Dikembalikan',
            default => $this->status,
        };
    }
}
