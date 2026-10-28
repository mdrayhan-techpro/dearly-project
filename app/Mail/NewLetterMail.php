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

class NewLetterMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $recipientUser,
        public Letter $letter
    ) {}

    public function envelope(): Envelope
    {
        $locale = $this->recipientUser->profile->preferred_language ?? 'en';
        $this->locale($locale);

        $senderNickname = $this->letter->sender->profile->nickname ?? __('A Pen-Pal', [], $locale);

        return new Envelope(
            subject: __('Dearly — A Sealed Letter Has Arrived From :name', ['name' => $senderNickname], $locale),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-letter',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
