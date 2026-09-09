<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\LogsActivity;

class Procurement extends Model
{
    use LogsActivity;

    protected string $logModelName = 'Pengadaan Alat';

    protected $fillable = [
        'code',
        'title',
        'user_id',
        'jurusan_id',
        'priority',
        'target_date',
        'status',
        'justification',
        'notes',
        'attachment',
        'total_estimated_cost',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'completed_at',
    ];

    protected $casts = [
        'target_date' => 'date',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
        'total_estimated_cost' => 'decimal:2',
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
     * Generate kode pengadaan otomatis: PR-YYYY-NNN
     */
    public static function generateCode(): string
    {
        $year = now()->year;
        $prefix = "PR-{$year}-";

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
     * Relasi: Pemohon pengadaan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Jurusan terkait.
     */
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }

    /**
     * Relasi: User yang menyetujui.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Relasi: Item barang yang diajukan.
     */
    public function items(): HasMany
    {
        return $this->hasMany(ProcurementItem::class);
    }

    /**
     * Scope: Filter berdasarkan user / role akses.
     */
    public function scopeAccessibleBy($query, User $user)
    {
        if ($user->isSuperadmin() || $user->isAdmin()) {
            return $query;
        }

        // Jika user jurusan, hanya lihat yang dibuatnya atau jurusan yang sama
        return $query->where(function ($q) use ($user) {
            $q->where('user_id', $user->id);
            if ($user->jurusan_id) {
                $q->orWhere('jurusan_id', $user->jurusan_id);
            }
        });
    }

    /**
     * Hitung ulang total biaya dari seluruh item.
     */
    public function recalculateTotal(): void
    {
        $total = $this->items()->sum('subtotal_price');
        $this->update(['total_estimated_cost' => $total]);
    }

    /**
     * Accessor label status dalam Bahasa Indonesia.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draf',
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'in_procurement' => 'Proses Pengadaan',
            'completed' => 'Selesai / Terealisasi',
            default => ucfirst($this->status),
        };
    }

    /**
     * Styling badge status.
     */
    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'draft' => ['bg' => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300', 'dot' => 'bg-gray-400', 'icon' => 'edit_note'],
            'pending' => ['bg' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300', 'dot' => 'bg-amber-500', 'icon' => 'hourglass_empty'],
            'approved' => ['bg' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300', 'dot' => 'bg-blue-500', 'icon' => 'check_circle'],
            'rejected' => ['bg' => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300', 'dot' => 'bg-red-500', 'icon' => 'cancel'],
            'in_procurement' => ['bg' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300', 'dot' => 'bg-purple-500', 'icon' => 'local_shipping'],
            'completed' => ['bg' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300', 'dot' => 'bg-emerald-500', 'icon' => 'task_alt'],
            default => ['bg' => 'bg-gray-100 text-gray-700', 'dot' => 'bg-gray-400', 'icon' => 'info'],
        };
    }

    /**
     * Styling badge prioritas.
     */
    public function getPriorityBadgeAttribute(): array
    {
        return match ($this->priority) {
            'rendah' => ['label' => 'Rendah', 'class' => 'bg-slate-100 text-slate-700 border-slate-300'],
            'sedang' => ['label' => 'Sedang', 'class' => 'bg-blue-50 text-blue-700 border-blue-200'],
            'tinggi' => ['label' => 'Tinggi', 'class' => 'bg-orange-50 text-orange-700 border-orange-200'],
            'mendesak' => ['label' => 'Mendesak', 'class' => 'bg-rose-50 text-rose-700 border-rose-200 font-semibold animate-pulse'],
            default => ['label' => ucfirst($this->priority), 'class' => 'bg-gray-100 text-gray-700'],
        };
    }

    /**
     * Format total estimasi biaya ke Rupiah.
     */
    public function getFormattedTotalCostAttribute(): string
    {
        return 'Rp ' . number_format($this->total_estimated_cost, 0, ',', '.');
    }
}
