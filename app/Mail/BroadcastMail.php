<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BroadcastMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $broadcastSubject;
    public string $broadcastContent;
    public string $academyName;

    /**
     * Create a new message instance.
     */
    public function __construct(string $subject, string $content, string $academyName = '')
    {
        $this->broadcastSubject = $subject;
        $this->broadcastContent = $content;
        $this->academyName = $academyName ?: config('app.name');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[PENGUMUMAN] ' . $this->broadcastSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.broadcast',
            with: [
                'broadcastSubject' => $this->broadcastSubject,
                'broadcastContent' => $this->broadcastContent,
                'academyName'      => $this->academyName,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
