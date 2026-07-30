<?php

namespace App\Jobs;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Notifications\Reservation\ReservationReminderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendReservationReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 300;

    public function __construct(public Reservation $reservation)
    {
    }

    public function handle(): void
    {
        if ($this->reservation->status !== ReservationStatus::CONFIRMED) {
            return;
        }

        $this->reservation->notify(new ReservationReminderNotification());

        $this->reservation->update(['sms_reminder_sent' => true]);
    }
}