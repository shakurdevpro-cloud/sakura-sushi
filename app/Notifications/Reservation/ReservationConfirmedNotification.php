<?php
// app/Notifications/Reservation/ReservationConfirmedNotification.php
namespace App\Notifications\Reservation;

use App\Enums\ReservationStatus;
use App\Notifications\Channels\LogSmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isConfirmed = $notifiable->status === ReservationStatus::CONFIRMED;
        $time = $notifiable->getRawOriginal('time');

        $mail = (new MailMessage)
            ->subject($isConfirmed ? 'Votre réservation est confirmée' : 'Nous avons bien reçu votre réservation')
            ->greeting("Bonjour {$notifiable->first_name},");

        if ($isConfirmed) {
            $mail->line("Votre réservation (réf. {$notifiable->reference}) est confirmée pour le {$notifiable->date->format('d/m/Y')} à {$time}.");
        } else {
            $mail->line("Votre demande de réservation (réf. {$notifiable->reference}) a bien été reçue pour le {$notifiable->date->format('d/m/Y')} à {$time}.")
                ->line('Elle est en attente de confirmation par notre équipe.');
        }

        return $mail->line("Nombre de convives : {$notifiable->guests}");
    }

    public function toSms(object $notifiable): string
    {
        $isConfirmed = $notifiable->status === ReservationStatus::CONFIRMED;
        $time = $notifiable->getRawOriginal('time');

        return $isConfirmed
            ? "Sakura Sushi: votre réservation {$notifiable->reference} est confirmée pour le {$notifiable->date->format('d/m/Y')} à {$time}."
            : "Sakura Sushi: nous avons bien reçu votre demande de réservation {$notifiable->reference}.";
    }
}
