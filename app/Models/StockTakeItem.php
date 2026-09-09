<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTakeItem extends Model
{
    protected $fillable = [
        'stock_take_id',
        'item_id',
        'system_quantity',
        'system_condition',
        'actual_quantity',
        'actual_condition',
        'difference',
        'notes',
        'checked_by',
        'checked_at',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
    ];

    /**
     * Relasi: Detail milik satu sesi stok opname.
     */
    public function stockTake(): BelongsTo
    {
        return $this->belongsTo(StockTake::class);
    }

    /**
     * Relasi: Detail merujuk ke satu item/barang.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Relasi: Dicek oleh user.
     */
    public function checkedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by');
    }

    /**
     * Warna selisih untuk tampilan.
     */
    public function getDifferenceColorAttribute(): string
    {
        if ($this->actual_quantity === null) return 'text-gray-400';
        if ($this->difference === 0) return 'text-green-600';
        if ($this->difference < 0) return 'text-red-600';
        return 'text-blue-600';
    }

    /**
     * Label selisih.
     */
    public function getDifferenceLabelAttribute(): string
    {
        if ($this->actual_quantity === null) return 'Belum dicek';
        if ($this->difference === 0) return 'Cocok';
        if ($this->difference < 0) return 'Kurang ' . abs($this->difference);
        return 'Lebih ' . $this->difference;
    }

    /**
     * Status pengecekan.
     */
    public function getIsCheckedAttribute(): bool
    {
        return $this->actual_quantity !== null;
    }
}
