<?php

namespace App\Listeners;

use App\Events\OrderCancelled;
use App\Notifications\Order\OrderCancelledNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendOrderCancelledEmail implements ShouldQueue
{
    public function handle(OrderCancelled $event): void
    {
        $email = $event->order->user?->email ?? $event->order->guest_email;

        if (! $email) {
            return;
        }

        Notification::route('mail', $email)->notify(new OrderCancelledNotification($event->order));
    }
}