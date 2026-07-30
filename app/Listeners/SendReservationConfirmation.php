<?php
// app/Listeners/SendReservationConfirmation.php
namespace App\Listeners;

use App\Events\ReservationCreated;
use App\Notifications\Reservation\ReservationConfirmedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendReservationConfirmation implements ShouldQueue
{
    public function handle(ReservationCreated $event): void
    {
        $event->reservation->notify(new ReservationConfirmedNotification());
    }
}