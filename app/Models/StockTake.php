<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockTake extends Model
{
    protected $fillable = [
        'code',
        'title',
        'description',
        'location_id',
        'started_by',
        'status',
        'started_at',
        'completed_at',
        'approved_by',
        'approved_at',
        'notes_summary',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /**
     * Boot: Auto-generate code on creating.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->code)) {
                $model->code = static::generateCode();
            }
        });
    }

    /**
     * Generate kode stok opname otomatis: SO-YYYY-NNN
     */
    public static function generateCode(): string
    {
        $year = now()->year;
        $prefix = "SO-{$year}-";

        $lastCode = static::where('code', 'like', "{$prefix}%")
            ->orderByDesc('code')
            ->value('code');

        if ($lastCode) {
            $lastNumber = (int) substr($lastCode, strlen($prefix));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Relasi: Stok opname memiliki banyak item detail.
     */
    public function items(): HasMany
    {
        return $this->hasMany(StockTakeItem::class);
    }

    /**
     * Relasi: Stok opname untuk satu lokasi.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Relasi: Dimulai oleh user.
     */
    public function startedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'started_by');
    }

    /**
     * Relasi: Disetujui oleh user.
     */
    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Label status (untuk tampilan).
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'in_progress' => 'Sedang Berjalan',
            'completed' => 'Selesai',
            'approved' => 'Disetujui',
            default => ucfirst($this->status),
        };
    }

    /**
     * Warna badge status.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'bg-gray-100 text-gray-700',
            'in_progress' => 'bg-blue-100 text-blue-700',
            'completed' => 'bg-yellow-100 text-yellow-700',
            'approved' => 'bg-green-100 text-green-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    /**
     * Total selisih dari semua item.
     */
    public function getTotalDiscrepancyAttribute(): int
    {
        return $this->items()->sum('difference');
    }

    /**
     * Jumlah item yang sudah dicek.
     */
    public function getCheckedCountAttribute(): int
    {
        return $this->items()->whereNotNull('actual_quantity')->count();
    }

    /**
     * Total jumlah item.
     */
    public function getTotalItemsCountAttribute(): int
    {
        return $this->items()->count();
    }

    /**
     * Persentase progress.
     */
    public function getProgressPercentAttribute(): int
    {
        $total = $this->total_items_count;
        if ($total === 0) return 0;
        return (int) round(($this->checked_count / $total) * 100);
    }

    /**
     * Jumlah item yang cocok (selisih = 0).
     */
    public function getMatchCountAttribute(): int
    {
        return $this->items()->whereNotNull('actual_quantity')->where('difference', 0)->count();
    }

    /**
     * Jumlah item yang kurang (selisih < 0).
     */
    public function getShortCountAttribute(): int
    {
        return $this->items()->whereNotNull('actual_quantity')->where('difference', '<', 0)->count();
    }

    /**
     * Jumlah item yang lebih (selisih > 0).
     */
    public function getOverCountAttribute(): int
    {
        return $this->items()->whereNotNull('actual_quantity')->where('difference', '>', 0)->count();
    }
}
