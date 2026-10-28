<?php

namespace App\Mail;

use App\Models\Letter;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LetterReadMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public bool $deleteWhenMissingModels = true;

    public function __construct(
        public User $senderUser,
        public Letter $letter
    ) {}

    public function envelope(): Envelope
    {
        $locale = $this->senderUser->profile->preferred_language ?? 'en';
        $this->locale($locale);

        $recipientNickname = $this->letter->recipient->profile->nickname ?? __('Your Pen-Pal', [], $locale);

        return new Envelope(
            subject: __('Dearly — :name Broke the Seal on Your Letter', ['name' => $recipientNickname], $locale),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.letter-read',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
