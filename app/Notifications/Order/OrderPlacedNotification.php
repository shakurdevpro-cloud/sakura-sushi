<?php

namespace App\Notifications\Order;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification implements ShouldQueue
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
            ->subject("Commande confirmée — {$this->order->reference}")
            ->greeting('Merci pour votre commande !')
            ->line("Référence : {$this->order->reference}")
            ->line("Total : $" . number_format($this->order->total / 100, 2));

        foreach ($this->order->items as $item) {
            $mail->line("{$item->quantity} × {$item->name} — $" . number_format($item->subtotal / 100, 2));
        }

        return $mail->line('Nous préparons votre commande.');
    }
}