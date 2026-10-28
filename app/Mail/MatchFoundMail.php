<?php

namespace App\Mail;

use App\Models\MatchResult;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MatchFoundMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public bool $deleteWhenMissingModels = true;

    public function __construct(
        public User $recipientUser,
        public MatchResult $matchResult,
        public User $matchedUser
    ) {}

    public function envelope(): Envelope
    {
        $locale = $this->recipientUser->profile->preferred_language ?? 'en';
        $this->locale($locale);

        return new Envelope(
            subject: __('Dearly — A Kindred Spirit Has Been Discovered (:score% Compatible)', [
                'score' => round($this->matchResult->compatibility_score),
            ], $locale),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.match-found',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
