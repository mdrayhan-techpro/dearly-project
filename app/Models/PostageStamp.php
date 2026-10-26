<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostageStamp extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'theme_category',
        'image_svg_path',
        'is_active',
        'order_index',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order_index' => 'integer',
    ];
}
