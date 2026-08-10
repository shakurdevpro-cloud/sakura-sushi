<?php
// app/Mail/NewsletterMail.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $newsletterSubject,
        public string $template,
        public array $data = []
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->newsletterSubject);
    }

    public function content(): Content
    {
        return new Content(
            view: $this->template,
            with: $this->data,
        );
    }
}