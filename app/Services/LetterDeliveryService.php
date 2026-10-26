<?php

namespace App\Services;

use App\Mail\NewLetterMail;
use App\Models\ApplicationSetting;
use App\Models\CorrespondenceConnection;
use App\Models\DailySendingCounter;
use App\Models\Letter;
use App\Models\LetterEvent;
use App\Models\LetterThread;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class LetterDeliveryService
{
    /**
     * Check if sender has reached daily sending limits
     */
    public function validateDailyLimits(User $sender, User $recipient): void
    {
        $today = now()->toDateString();
        $counter = DailySendingCounter::firstOrCreate(
            ['user_id' => $sender->id, 'date' => $today],
            ['letters_sent_count' => 0, 'first_contacts_count' => 0]
        );

        $maxLetters = (int) ApplicationSetting::get('max_letters_per_day', 5);
        if ($counter->letters_sent_count >= $maxLetters) {
            throw ValidationException::withMessages([
                'daily_limit' => ["You have reached your daily postal limit of {$maxLetters} letters. In the spirit of slow correspondence, the post office will accept new dispatches tomorrow."],
            ]);
        }

        // Check if first contact
        $hasPriorLetters = Letter::where(function ($q) use ($sender, $recipient) {
            $q->where('sender_id', $sender->id)->where('recipient_id', $recipient->id);
        })->whereIn('status', ['sent', 'delivered', 'read'])->exists();

        if (!$hasPriorLetters) {
            $maxFirstContacts = (int) ApplicationSetting::get('max_first_contacts_per_day', 2);
            if ($counter->first_contacts_count >= $maxFirstContacts) {
                throw ValidationException::withMessages([
                    'daily_limit' => ["You have reached your limit of {$maxFirstContacts} new pen-pal introductions today. Take your time getting to know your new correspondents!"],
                ]);
            }
        }
    }

    /**
     * Dispatch a letter from sender to recipient
     */
    public function dispatchLetter(Letter $letter, User $sender, User $recipient): Letter
    {
        $this->validateDailyLimits($sender, $recipient);

        return DB::transaction(function () use ($letter, $sender, $recipient) {
            // Find or create letter thread
            $thread = LetterThread::findOrCreateBetween($sender->id, $recipient->id);
            $thread->update(['last_letter_at' => now()]);

            // Update letter status
            $letter->update([
                'thread_id' => $thread->id,
                'recipient_id' => $recipient->id,
                'status' => 'delivered', // Delivered to recipient's postbox
                'sent_at' => now(),
                'delivered_at' => now(),
            ]);

            // Record events
            LetterEvent::create([
                'letter_id' => $letter->id,
                'event_type' => 'sealed',
                'recorded_at' => now(),
            ]);

            LetterEvent::create([
                'letter_id' => $letter->id,
                'event_type' => 'dispatched',
                'recorded_at' => now(),
            ]);

            LetterEvent::create([
                'letter_id' => $letter->id,
                'event_type' => 'delivered',
                'recorded_at' => now(),
                'metadata' => ['mailbox_id' => $recipient->id],
            ]);

            // Increment sender's daily counter
            $today = now()->toDateString();
            $counter = DailySendingCounter::firstOrCreate(
                ['user_id' => $sender->id, 'date' => $today],
                ['letters_sent_count' => 0, 'first_contacts_count' => 0]
            );

            $hasPriorLetters = Letter::where(function ($q) use ($sender, $recipient) {
                $q->where('sender_id', $sender->id)->where('recipient_id', $recipient->id);
            })->where('id', '!=', $letter->id)->whereIn('status', ['sent', 'delivered', 'read'])->exists();

            $counter->increment('letters_sent_count');
            if (!$hasPriorLetters) {
                $counter->increment('first_contacts_count');
            }

            // Create in-app notification for the recipient
            Notification::create([
                'user_id' => $recipient->id,
                'type' => 'letter_received',
                'title' => 'A New Letter Has Arrived',
                'message' => "A sealed letter from {$sender->nickname} has been placed in your mailbox.",
                'action_url' => route('mailbox.index'),
            ]);

            // Queue outbound email notification
            try {
                Mail::to($recipient->email)->queue(new NewLetterMail($recipient, $letter));
            } catch (\Throwable $e) {
                Log::warning("Failed to queue NewLetterMail for user {$recipient->id}: {$e->getMessage()}");
            }

            return $letter->fresh();
        });
    }
}
