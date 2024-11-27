<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SamahangNayonMailer extends Mailable
{
    use Queueable, SerializesModels;

    public $paymentLink;
    public function __construct($paymentLink)
    {
        $this->paymentLink = $paymentLink;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Samahang Nayon Mailer',
        );
    }


    public function content(): Content
    {
        return new Content(
            view: 'emails.payment',

        );
    }


    public function attachments(): array
    {
        return [];
    }
}
