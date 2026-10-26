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

    public function __construct(
        public User $senderUser,
        public Letter $letter
    ) {}

    public function envelope(): Envelope
    {
        $recipientNickname = $this->letter->recipient->profile->nickname ?? 'Your Pen-Pal';

        return new Envelope(
            subject: 'Dearly — '.$recipientNickname.' Broke the Seal on Your Letter',
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
