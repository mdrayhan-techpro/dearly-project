<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LetterThread extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_one_id',
        'participant_two_id',
        'last_letter_at',
    ];

    protected $casts = [
        'last_letter_at' => 'datetime',
    ];

    public function participantOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'participant_one_id');
    }

    public function participantTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'participant_two_id');
    }

    public function letters(): HasMany
    {
        return $this->hasMany(Letter::class, 'thread_id')->orderBy('created_at', 'asc');
    }

    public static function findOrCreateBetween(int $userA, int $userB): self
    {
        $one = min($userA, $userB);
        $two = max($userA, $userB);

        return static::firstOrCreate([
            'participant_one_id' => $one,
            'participant_two_id' => $two,
        ]);
    }
}
