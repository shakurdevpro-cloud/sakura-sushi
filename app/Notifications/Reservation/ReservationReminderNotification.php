<?php
// app/Notifications/Reservation/ReservationReminderNotification.php
namespace App\Notifications\Reservation;

use App\Notifications\Channels\LogSmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        $channels = ['mail'];

        if ($notifiable->sms_consent) {
            $channels[] = LogSmsChannel::class;
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $time = $notifiable->getRawOriginal('time');

        return (new MailMessage)
            ->subject('Rappel : votre réservation dans 2h')
            ->greeting("Bonjour {$notifiable->first_name},")
            ->line("Petit rappel : votre réservation (réf. {$notifiable->reference}) est prévue aujourd'hui à {$time}.")
            ->line("Nombre de convives : {$notifiable->guests}")
            ->line("À tout à l'heure !");
    }

    public function toSms(object $notifiable): string
    {
        $time = $notifiable->getRawOriginal('time');

        return "Sakura Sushi: rappel, votre réservation {$notifiable->reference} est dans 2h ({$time}). À bientôt !";
    }
}