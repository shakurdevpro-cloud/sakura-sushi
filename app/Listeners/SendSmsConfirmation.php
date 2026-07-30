<?php
// app/Listeners/SendSmsConfirmation.php
namespace App\Listeners;

use App\Events\ReservationCreated;
use App\Notifications\Channels\LogSmsChannel;
use App\Notifications\Reservation\ReservationConfirmedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSmsConfirmation implements ShouldQueue
{
    public function handle(ReservationCreated $event): void
    {
        if (! $event->reservation->sms_consent) {
            return;
        }

        (new LogSmsChannel())->send($event->reservation, new ReservationConfirmedNotification());
    }
}