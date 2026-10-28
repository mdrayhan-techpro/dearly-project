<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'role',
        'is_suspended',
        'suspension_reason',
        'suspended_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_suspended' => 'boolean',
            'suspended_at' => 'datetime',
        ];
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function mailingCode(): HasOne
    {
        return $this->hasOne(MailingCode::class);
    }

    public function sentLetters(): HasMany
    {
        return $this->hasMany(Letter::class, 'sender_id');
    }

    public function receivedLetters(): HasMany
    {
        return $this->hasMany(Letter::class, 'recipient_id');
    }

    public function matches(): HasMany
    {
        return $this->hasMany(MatchResult::class, 'user_id');
    }

    public function blockedUsers(): HasMany
    {
        return $this->hasMany(BlockedUser::class, 'user_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function questionnaireAttempts(): HasMany
    {
        return $this->hasMany(UserQuestionnaireAttempt::class, 'user_id');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function isModerator(): bool
    {
        return in_array($this->role, ['super_admin', 'admin', 'moderator']);
    }

    public function hasRole(string|array $roles): bool
    {
        $roles = (array) $roles;
        return in_array($this->role, $roles);
    }

    public function isSuspended(): bool
    {
        return (bool) $this->is_suspended;
    }

    public function hasBlocked(int $userId): bool
    {
        return $this->blockedUsers()->where('blocked_user_id', $userId)->exists();
    }

    public function isBlockedBy(int $userId): bool
    {
        return BlockedUser::where('user_id', $userId)->where('blocked_user_id', $this->id)->exists();
    }

    public function getNicknameAttribute(): string
    {
        return $this->profile?->nickname ?? 'Friend of Dearly';
    }
}
