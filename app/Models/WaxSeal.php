<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaxSeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'color_hex',
        'symbol_svg',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
