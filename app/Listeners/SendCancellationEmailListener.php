<?php
// app/Listeners/SendCancellationEmailListener.php
namespace App\Listeners;

use App\Events\ReservationCancelled;
use App\Notifications\Reservation\ReservationCancelledNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCancellationEmailListener implements ShouldQueue
{
    public function handle(ReservationCancelled $event): void
    {
        $event->reservation->notify(new ReservationCancelledNotification());
    }
}