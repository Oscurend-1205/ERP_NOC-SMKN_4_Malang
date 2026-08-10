<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $fillable = [
        'name',
        'kode_jurusan',
        'kepala_jurusan',
        'description',
        'is_active',
    ];
}
