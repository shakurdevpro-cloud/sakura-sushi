<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Notifications\Order\OrderPlacedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendOrderConfirmationEmail implements ShouldQueue
{
    public function handle(OrderPlaced $event): void
    {
        $email = $event->order->user?->email ?? $event->order->guest_email;

        if (! $email) {
            return;
        }

        Notification::route('mail', $email)->notify(new OrderPlacedNotification($event->order));
    }
}