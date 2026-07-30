<?php
// app/Listeners/SendConfirmedEmailListener.php
namespace App\Listeners;

use App\Events\ReservationConfirmed;
use App\Notifications\Reservation\ReservationConfirmedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendConfirmedEmailListener implements ShouldQueue
{
    public function handle(ReservationConfirmed $event): void
    {
        $event->reservation->notify(new ReservationConfirmedNotification());
    }
}