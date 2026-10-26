<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nickname',
        'age',
        'gender',
        'preferred_language',
        'country',
        'bio',
        'interests',
        'communication_preferences',
        'is_matching_paused',
        'profile_visibility',
        'is_onboarded',
    ];

    protected $casts = [
        'interests' => 'array',
        'communication_preferences' => 'array',
        'is_matching_paused' => 'boolean',
        'is_onboarded' => 'boolean',
        'age' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
