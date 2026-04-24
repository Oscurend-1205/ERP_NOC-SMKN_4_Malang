<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $fillable = [
        'name',
        'code',
        'serial_number',
        'brand',
        'model',
        'category_id',
        'location_id',
        'quantity',
        'condition',
        'status',
        'purchase_date',
        'purchase_price',
        'notes',
        'image',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
    ];

    /**
     * Relasi: Barang milik satu kategori.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi: Barang berada di satu lokasi.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Relasi: Barang memiliki banyak riwayat pergerakan.
     */
    public function movements(): HasMany
    {
        return $this->hasMany(ItemMovement::class);
    }

    /**
     * Label kondisi barang (untuk tampilan).
     */
    public function getConditionLabelAttribute(): string
    {
        return match ($this->condition) {
            'baik' => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat' => 'Rusak Berat',
            'hilang' => 'Hilang',
            default => $this->condition,
        };
    }

    /**
     * Label status barang (untuk tampilan).
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'tersedia' => 'Tersedia',
            'dipinjam' => 'Dipinjam',
            'maintenance' => 'Maintenance',
            'dimusnahkan' => 'Dimusnahkan',
            default => $this->status,
        };
    }
}
