<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Notification extends Model
{
    /**
     * Indicates the primary key type is UUID string.
     */
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'type',
        'icon',
        'title',
        'message',
        'action_url',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Boot: Auto-generate UUID on creating.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Relasi: Notifikasi milik satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Filter notifikasi belum dibaca.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope: Filter by user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Tandai notifikasi sudah dibaca.
     */
    public function markAsRead(): self
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return $this;
    }

    /**
     * Warna badge berdasarkan type.
     */
    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'success' => 'bg-green-100 text-green-700',
            'warning' => 'bg-yellow-100 text-yellow-700',
            'danger' => 'bg-red-100 text-red-700',
            'info' => 'bg-blue-100 text-blue-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    /**
     * Dot color untuk indikator.
     */
    public function getDotColorAttribute(): string
    {
        return match ($this->type) {
            'success' => 'bg-green-500',
            'warning' => 'bg-yellow-500',
            'danger' => 'bg-red-500',
            'info' => 'bg-blue-500',
            default => 'bg-gray-500',
        };
    }

    /**
     * Waktu relatif (e.g. "5 menit yang lalu").
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Static helper: Kirim notifikasi ke user.
     */
    public static function send(
        int $userId,
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        string $icon = 'notifications'
    ): self {
        return static::create([
            'user_id' => $userId,
            'type' => $type,
            'icon' => $icon,
            'title' => $title,
            'message' => $message,
            'action_url' => $actionUrl,
        ]);
    }

    /**
     * Static helper: Kirim notifikasi ke semua user dengan role tertentu.
     */
    public static function sendToRole(
        string $role,
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        string $icon = 'notifications'
    ): void {
        $users = User::where('role', $role)->where('is_active', true)->get();

        foreach ($users as $user) {
            static::send($user->id, $type, $title, $message, $actionUrl, $icon);
        }
    }

    /**
     * Static helper: Kirim notifikasi ke semua Admin & Superadmin.
     */
    public static function sendToAdmins(
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        string $icon = 'notifications'
    ): void {
        $users = User::whereIn('role', ['Superadmin', 'Admin'])->where('is_active', true)->get();

        foreach ($users as $user) {
            static::send($user->id, $type, $title, $message, $actionUrl, $icon);
        }
    }
}
