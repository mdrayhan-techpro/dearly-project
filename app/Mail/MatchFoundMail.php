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

    public function __construct(
        public User $recipientUser,
        public MatchResult $matchResult,
        public User $matchedUser
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Dearly — A Kindred Spirit Has Been Discovered ('.round($this->matchResult->compatibility_score).'% Compatible)',
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
