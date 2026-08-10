<?php

namespace App\Mail;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Subscriber $subscriber)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Confirmez votre abonnement à la newsletter Sakura Sushi');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter.confirm',
            with: [
                'name' => $this->subscriber->name,
                'confirmUrl' => url('/api/v1/newsletter/confirm/' . $this->subscriber->token),
            ],
        );
    }
}