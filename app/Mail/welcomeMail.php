<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class welcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $academy;
    public $temporaryPassword;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $academy = null, $temporaryPassword = null)
    {
        $this->user = $user;
        $this->academy = $academy;
        $this->temporaryPassword = $temporaryPassword;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $academyName = $this->academy ? $this->academy->name : config('app.name');
        
        return new Envelope(
            subject: 'Selamat Datang di ' . $academyName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.welcomeMail',
            with: [
                'user' => $this->user,
                'academy' => $this->academy,
                'temporaryPassword' => $this->temporaryPassword,
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
