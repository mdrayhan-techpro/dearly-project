<?php

namespace App\Models;

use App\Mail\LetterReadMail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Letter extends Model
{
    use HasFactory;

    protected $fillable = [
        'thread_id',
        'sender_id',
        'recipient_id',
        'reply_to_letter_id',
        'title',
        'body',
        'greeting',
        'signoff',
        'stationery_theme_id',
        'postage_stamp_id',
        'wax_seal_id',
        'status',
        'is_read',
        'read_at',
        'sent_at',
        'delivered_at',
        'sender_deleted',
        'recipient_deleted',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'sender_deleted' => 'boolean',
        'recipient_deleted' => 'boolean',
    ];

    public function thread(): BelongsTo
    {
        return $this->belongsTo(LetterThread::class, 'thread_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(Letter::class, 'reply_to_letter_id');
    }

    public function stationeryTheme(): BelongsTo
    {
        return $this->belongsTo(StationeryTheme::class, 'stationery_theme_id');
    }

    public function postageStamp(): BelongsTo
    {
        return $this->belongsTo(PostageStamp::class, 'postage_stamp_id');
    }

    public function waxSeal(): BelongsTo
    {
        return $this->belongsTo(WaxSeal::class, 'wax_seal_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(LetterEvent::class);
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where(function ($q) use ($userId) {
            $q->where(function ($sub) use ($userId) {
                $sub->where('sender_id', $userId)->where('sender_deleted', false);
            })->orWhere(function ($sub) use ($userId) {
                $sub->where('recipient_id', $userId)->where('recipient_deleted', false)->where('status', '!=', 'draft');
            });
        });
    }

    public function scopeInboxFor(Builder $query, int $userId): Builder
    {
        return $query->where('recipient_id', $userId)
            ->where('recipient_deleted', false)
            ->whereIn('status', ['sent', 'delivered', 'read', 'archived'])
            ->orderBy('created_at', 'desc');
    }

    public function scopeSentBy(Builder $query, int $userId): Builder
    {
        return $query->where('sender_id', $userId)
            ->where('sender_deleted', false)
            ->whereIn('status', ['queued', 'sent', 'delivered', 'read', 'archived'])
            ->orderBy('created_at', 'desc');
    }

    public function scopeDraftsFor(Builder $query, int $userId): Builder
    {
        return $query->where('sender_id', $userId)
            ->where('status', 'draft')
            ->where('sender_deleted', false)
            ->orderBy('updated_at', 'desc');
    }

    public function canBeAccessedBy(int $userId): bool
    {
        if ($this->sender_id === $userId && !$this->sender_deleted) {
            return true;
        }

        if ($this->recipient_id === $userId && !$this->recipient_deleted && $this->status !== 'draft') {
            return true;
        }

        return false;
    }

    public function markAsRead(): bool
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
                'status' => 'read',
            ]);

            $this->events()->create([
                'event_type' => 'opened',
                'recorded_at' => now(),
                'metadata' => ['read_by' => $this->recipient_id],
            ]);

            // Create notification for the sender
            Notification::create([
                'user_id' => $this->sender_id,
                'type' => 'letter_read',
                'title' => 'Your Letter Has Been Read',
                'message' => "{$this->recipient->nickname} has opened and read your letter: \"{$this->title}\".",
                'action_url' => route('letters.show', $this->id),
            ]);

            // Queue email read receipt to the original sender
            try {
                if ($this->sender && $this->sender->email) {
                    Mail::to($this->sender->email)->queue(new LetterReadMail($this->sender, $this));
                }
            } catch (\Throwable $e) {
                Log::warning("Failed to queue LetterReadMail for user {$this->sender_id}: {$e->getMessage()}");
            }

            return true;
        }

        return false;
    }
}
