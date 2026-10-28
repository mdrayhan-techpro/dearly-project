<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CorrespondenceConnection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_one_id',
        'user_two_id',
        'status',
        'connected_at',
    ];

    protected $casts = [
        'connected_at' => 'datetime',
    ];

    public function userOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public static function areConnected(int $userA, int $userB): bool
    {
        return static::where(function ($q) use ($userA, $userB) {
            $q->where(function ($pair) use ($userA, $userB) {
                $pair->where('user_one_id', $userA)->where('user_two_id', $userB);
            })->orWhere(function ($pair) use ($userA, $userB) {
                $pair->where('user_one_id', $userB)->where('user_two_id', $userA);
            });
        })->where('status', 'connected')->exists();
    }
}
