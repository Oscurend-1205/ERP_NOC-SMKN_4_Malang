<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * Trait LogsActivity
 * 
 * Gunakan trait ini di model untuk otomatis mencatat
 * setiap perubahan data ke tabel activity_logs.
 * 
 * Usage: use \App\Traits\LogsActivity;
 */
trait LogsActivity
{
    /**
     * Boot trait: hook ke Eloquent events.
     */
    public static function bootLogsActivity(): void
    {
        // Log saat record dibuat
        static::created(function ($model) {
            $model->logActivity('created', $model->getCreatedDescription(), null, $model->getLoggableAttributes());
        });

        // Log saat record diupdate
        static::updated(function ($model) {
            $oldValues = $model->getChangedOldValues();
            $newValues = $model->getChangedNewValues();

            // Hanya log jika ada perubahan yang berarti
            if (!empty($oldValues)) {
                $model->logActivity('updated', $model->getUpdatedDescription(), $oldValues, $newValues);
            }
        });

        // Log saat record dihapus
        static::deleted(function ($model) {
            $model->logActivity('deleted', $model->getDeletedDescription(), $model->getLoggableAttributes(), null);
        });
    }

    /**
     * Catat aktivitas ke tabel activity_logs.
     */
    protected function logActivity(string $action, string $description, ?array $oldValues, ?array $newValues): void
    {
        try {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'model_type' => get_class($this),
                'model_id' => $this->getKey(),
                'description' => $description,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        } catch (\Exception $e) {
            // Jangan sampai logging error mengganggu operasi utama
            \Log::warning('Activity logging failed: ' . $e->getMessage());
        }
    }

    /**
     * Ambil atribut yang layak di-log (exclude sensitive fields).
     */
    protected function getLoggableAttributes(): array
    {
        $excluded = $this->getExcludedLogFields();
        $attributes = $this->attributesToArray();

        return array_diff_key($attributes, array_flip($excluded));
    }

    /**
     * Ambil old values dari field yang berubah.
     */
    protected function getChangedOldValues(): array
    {
        $excluded = $this->getExcludedLogFields();
        $changed = $this->getChanges();
        $original = $this->getOriginal();
        $oldValues = [];

        foreach ($changed as $key => $value) {
            if (!in_array($key, $excluded) && $key !== 'updated_at') {
                $oldValues[$key] = $original[$key] ?? null;
            }
        }

        return $oldValues;
    }

    /**
     * Ambil new values dari field yang berubah.
     */
    protected function getChangedNewValues(): array
    {
        $excluded = $this->getExcludedLogFields();
        $changed = $this->getChanges();
        $newValues = [];

        foreach ($changed as $key => $value) {
            if (!in_array($key, $excluded) && $key !== 'updated_at') {
                $newValues[$key] = $value;
            }
        }

        return $newValues;
    }

    /**
     * Field yang tidak boleh di-log (sensitive data).
     * Override di model untuk customize.
     */
    protected function getExcludedLogFields(): array
    {
        return property_exists($this, 'excludedLogFields')
            ? $this->excludedLogFields
            : ['password', 'remember_token', 'created_at', 'updated_at'];
    }

    /**
     * Nama model yang user-friendly untuk deskripsi.
     * Override di model untuk customize.
     */
    protected function getLogModelName(): string
    {
        return property_exists($this, 'logModelName')
            ? $this->logModelName
            : class_basename($this);
    }

    /**
     * Identifier unik untuk deskripsi (nama/kode barang, dll).
     * Override di model untuk customize.
     */
    protected function getLogIdentifier(): string
    {
        // Coba beberapa field umum sebagai identifier
        return $this->name ?? $this->code ?? $this->title ?? "#{$this->getKey()}";
    }

    /**
     * Deskripsi saat record dibuat.
     */
    protected function getCreatedDescription(): string
    {
        $user = Auth::user()->name ?? 'Sistem';
        return "{$user} menambahkan {$this->getLogModelName()} \"{$this->getLogIdentifier()}\"";
    }

    /**
     * Deskripsi saat record diupdate.
     */
    protected function getUpdatedDescription(): string
    {
        $user = Auth::user()->name ?? 'Sistem';
        return "{$user} memperbarui {$this->getLogModelName()} \"{$this->getLogIdentifier()}\"";
    }

    /**
     * Deskripsi saat record dihapus.
     */
    protected function getDeletedDescription(): string
    {
        $user = Auth::user()->name ?? 'Sistem';
        return "{$user} menghapus {$this->getLogModelName()} \"{$this->getLogIdentifier()}\"";
    }
}
