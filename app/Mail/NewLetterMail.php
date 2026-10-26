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
        $senderNickname = $this->letter->sender->profile->nickname ?? 'A Pen-Pal';

        return new Envelope(
            subject: 'Dearly — A Sealed Letter Has Arrived From '.$senderNickname,
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
