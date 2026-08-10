<?php
// app/Jobs/SendNewsletterBatchJob.php
namespace App\Jobs;

use App\Mail\NewsletterMail;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewsletterBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $subject,
        public string $template,
        public array $data = []
    ) {
    }

    public function handle(): void
    {
        // Traiter par batch de 500 pour ne pas saturer la mémoire
        Subscriber::confirmed()->chunk(500, function ($subscribers) {
            foreach ($subscribers as $subscriber) {
                Mail::to($subscriber->email)->queue(
                    new NewsletterMail($this->subject, $this->template, $this->data)
                );
            }
        });
    }
}