<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemMovement extends Model
{
    protected $fillable = [
        'item_id',
        'user_id',
        'type',
        'from_location_id',
        'to_location_id',
        'quantity',
        'notes',
        'movement_date',
    ];

    protected $casts = [
        'movement_date' => 'date',
    ];

    /**
     * Relasi: Pergerakan milik satu barang.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Relasi: Pergerakan dilakukan oleh satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Lokasi asal.
     */
    public function fromLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    /**
     * Relasi: Lokasi tujuan.
     */
    public function toLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }

    /**
     * Label tipe pergerakan (untuk tampilan).
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'masuk' => 'Barang Masuk',
            'keluar' => 'Barang Keluar',
            'pindah' => 'Pindah Lokasi',
            'maintenance' => 'Maintenance',
            'rusak' => 'Rusak',
            'musnahkan' => 'Dimusnahkan',
            default => $this->type,
        };
    }
}
