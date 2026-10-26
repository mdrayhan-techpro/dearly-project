<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StationeryTheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'background_css',
        'text_color',
        'border_style',
        'is_active',
        'order_index',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order_index' => 'integer',
    ];
}
