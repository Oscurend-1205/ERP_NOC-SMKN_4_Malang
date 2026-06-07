<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KondisiBarang extends Model
{
    protected $fillable = [
        'name',
        'label_color',
        'description',
        'is_active',
    ];
}
