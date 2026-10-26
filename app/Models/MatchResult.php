<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'matched_user_id',
        'compatibility_score',
        'shared_dimensions',
        'is_notified',
        'status',
    ];

    protected $casts = [
        'compatibility_score' => 'decimal:2',
        'shared_dimensions' => 'array',
        'is_notified' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function matchedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'matched_user_id');
    }
}
