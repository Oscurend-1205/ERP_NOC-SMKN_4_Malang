<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'prefix',
        'last_code_number',
    ];

    protected $casts = [
        'last_code_number' => 'integer',
    ];

    /**
     * Relasi: Kategori memiliki banyak barang.
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
