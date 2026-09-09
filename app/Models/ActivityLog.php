<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /**
     * Relasi: Log milik satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Filter by model type dan ID.
     */
    public function scopeForModel($query, $model)
    {
        return $query->where('model_type', get_class($model))
                     ->where('model_id', $model->getKey());
    }

    /**
     * Scope: Filter by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Filter by date range.
     */
    public function scopeInPeriod($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    /**
     * Scope: Filter by action type.
     */
    public function scopeOfAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Accessor: Ringkasan perubahan (diff old vs new).
     */
    public function getChangeSummaryAttribute(): array
    {
        $changes = [];
        $old = $this->old_values ?? [];
        $new = $this->new_values ?? [];

        $allKeys = array_unique(array_merge(array_keys($old), array_keys($new)));

        foreach ($allKeys as $key) {
            $oldVal = $old[$key] ?? null;
            $newVal = $new[$key] ?? null;

            if ($oldVal !== $newVal) {
                $changes[$key] = [
                    'old' => $oldVal,
                    'new' => $newVal,
                ];
            }
        }

        return $changes;
    }

    /**
     * Label aksi (untuk tampilan).
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'created' => 'Dibuat',
            'updated' => 'Diperbarui',
            'deleted' => 'Dihapus',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'login' => 'Login',
            'logout' => 'Logout',
            'exported' => 'Diekspor',
            default => ucfirst($this->action),
        };
    }

    /**
     * Warna badge aksi.
     */
    public function getActionColorAttribute(): string
    {
        return match ($this->action) {
            'created' => 'bg-green-100 text-green-700',
            'updated' => 'bg-blue-100 text-blue-700',
            'deleted' => 'bg-red-100 text-red-700',
            'approved' => 'bg-emerald-100 text-emerald-700',
            'rejected' => 'bg-rose-100 text-rose-700',
            'login' => 'bg-yellow-100 text-yellow-700',
            'logout' => 'bg-gray-100 text-gray-700',
            'exported' => 'bg-purple-100 text-purple-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    /**
     * Nama model singkat (tanpa namespace).
     */
    public function getModelNameAttribute(): string
    {
        if (!$this->model_type) return '-';
        return class_basename($this->model_type);
    }

    /**
     * Static helper: Catat aktivitas dari mana saja.
     */
    public static function record(
        string $action,
        string $description,
        $model = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?int $userId = null
    ): self {
        return static::create([
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->getKey(),
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
