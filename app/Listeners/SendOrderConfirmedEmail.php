<?php

namespace App\Listeners;

use App\Events\OrderConfirmed;
use App\Notifications\Order\OrderConfirmedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendOrderConfirmedEmail implements ShouldQueue
{
    public function handle(OrderConfirmed $event): void
    {
        $email = $event->order->user?->email ?? $event->order->guest_email;

        if (! $email) {
            return;
        }

        Notification::route('mail', $email)->notify(new OrderConfirmedNotification($event->order));
    }
}   