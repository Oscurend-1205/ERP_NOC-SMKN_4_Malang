<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ScanSession extends Model
{
    protected $fillable = [
        'token',
        'created_by',
        'expired_at',
        'is_used',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    /**
     * Generate token unik baru dengan masa berlaku.
     */
    public static function generateToken(int $userId, int $expiryMinutes = 10): self
    {
        return self::create([
            'token' => Str::random(48),
            'created_by' => $userId,
            'expired_at' => now()->addMinutes($expiryMinutes),
        ]);
    }

    /**
     * Cek apakah token masih valid (belum expired & belum dipakai).
     */
    public function isValid(): bool
    {
        return !$this->is_used && $this->expired_at->isFuture();
    }

    /**
     * Relasi: Session dibuat oleh satu user (admin).
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi: Session memiliki banyak peminjaman.
     */
    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'session_token', 'token');
    }
}
