<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcurementItem extends Model
{
    protected $fillable = [
        'procurement_id',
        'category_id',
        'item_name',
        'specification',
        'quantity',
        'unit',
        'estimated_unit_price',
        'subtotal_price',
        'reference_url',
        'status',
        'received_quantity',
        'notes',
    ];

    protected $casts = [
        'estimated_unit_price' => 'decimal:2',
        'subtotal_price' => 'decimal:2',
        'quantity' => 'integer',
        'received_quantity' => 'integer',
    ];

    /**
     * Boot: Hitung subtotal otomatis saat create atau update.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->subtotal_price = $item->quantity * $item->estimated_unit_price;
        });

        static::saved(function ($item) {
            $item->procurement->recalculateTotal();
        });

        static::deleted(function ($item) {
            $item->procurement->recalculateTotal();
        });
    }

    /**
     * Relasi: Item milik satu pengajuan.
     */
    public function procurement(): BelongsTo
    {
        return $this->belongsTo(Procurement::class);
    }

    /**
     * Relasi: Kategori barang.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Format harga satuan ke Rupiah.
     */
    public function getFormattedUnitPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->estimated_unit_price, 0, ',', '.');
    }

    /**
     * Format subtotal ke Rupiah.
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal_price, 0, ',', '.');
    }
}
