<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationMail extends Mailable
{
    use Queueable, SerializesModels;


    public string $messageBody;


    public function __construct(string $messageBody)
    {

        $this->messageBody = $messageBody;
    }


    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Your One-Time Verification Code",
        );
    }


    public function content(): Content
    {
        return new Content(
            view: 'emails.verification',
            with: [
                'messageBody' => $this->messageBody,
            ]
        );
    }


    public function attachments(): array
    {
        return [];
    }
}
