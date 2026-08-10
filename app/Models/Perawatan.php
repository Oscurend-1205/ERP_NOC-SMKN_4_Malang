<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perawatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'user_id',
        'jenis_perawatan',
        'tanggal_pengajuan',
        'tanggal_selesai',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'Menunggu Persetujuan',
            'proses' => 'Sedang Berlangsung',
            'selesai' => 'Selesai',
            default => ucfirst($this->status),
        };
    }
    
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'bg-[#FFF8E1] text-[#FFA500]',
            'proses' => 'bg-[#E9EDF7] text-[#2D60FF]',
            'selesai' => 'bg-[#E6F4EA] text-[#4CAF50]',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
