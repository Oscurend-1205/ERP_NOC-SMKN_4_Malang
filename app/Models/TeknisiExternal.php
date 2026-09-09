<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeknisiExternal extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'telepon',
        'email',
        'alamat',
        'spesialisasi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi: Teknisi memiliki banyak perawatan.
     */
    public function perawatans()
    {
        return $this->hasMany(Perawatan::class);
    }
}
