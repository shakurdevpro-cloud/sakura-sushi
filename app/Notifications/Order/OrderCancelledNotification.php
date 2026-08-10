<?php

namespace App\Notifications\Order;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCancelledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject("Votre commande {$this->order->reference} a été annulée")
            ->line("Votre commande {$this->order->reference} a été annulée.");

        if ($this->order->cancel_reason) {
            $mail->line("Motif : {$this->order->cancel_reason}");
        }

        return $mail;
    }
}