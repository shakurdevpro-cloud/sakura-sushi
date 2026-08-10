<?php
// app/Services/NewsletterService.php
namespace App\Services;

use App\Jobs\SendNewsletterConfirmationJob;
use App\Models\Subscriber;
use Illuminate\Support\Str;

class NewsletterService
{
    public function subscribe(string $email, ?string $name = null, ?string $source = null): Subscriber
    {
        $subscriber = Subscriber::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'source' => $source,
                'token' => Str::random(64),
                'unsubscribed_at' => null,
            ]
        );

        SendNewsletterConfirmationJob::dispatch($subscriber);

        return $subscriber;
    }

    public function confirm(string $token): Subscriber
    {
        $subscriber = Subscriber::where('token', $token)->firstOrFail();
        $subscriber->update(['confirmed_at' => now()]);

        return $subscriber->fresh();
    }

    public function unsubscribe(string $token): void
    {
        // Jamais supprimer — RGPD : conserver la preuve du désinscription
        Subscriber::where('token', $token)->update(['unsubscribed_at' => now()]);
    }
}
