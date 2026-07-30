<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $token)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = config('app.frontend_url', config('app.url'))
            . '/reset-password?token=' . $this->token
            . '&email=' . urlencode($notifiable->email);

        return (new MailMessage)
            ->subject('Réinitialisation de mot de passe')
            ->line('Vous avez demandé une réinitialisation de mot de passe.')
            ->action('Réinitialiser', $url)
            ->line('Ce lien expire dans 60 minutes.');
    }
}