<?php
// app/Notifications/Reservation/ReservationCancelledNotification.php
namespace App\Notifications\Reservation;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationCancelledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Votre réservation a été annulée')
            ->greeting("Bonjour {$notifiable->first_name},")
            ->line("Votre réservation (réf. {$notifiable->reference}) prévue le {$notifiable->date->format('d/m/Y')} a été annulée.");

        if ($notifiable->cancel_reason) {
            $mail->line("Motif : {$notifiable->cancel_reason}");
        }

        return $mail->line("N'hésitez pas à réserver un nouveau créneau quand vous le souhaitez.");
    }
}