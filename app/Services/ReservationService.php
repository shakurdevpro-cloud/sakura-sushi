<?php

namespace App\Services;

use App\Enums\ReservationStatus;
use App\Events\ReservationCancelled;
use App\Events\ReservationConfirmed;
use App\Events\ReservationCreated;
use App\Exceptions\SlotUnavailableException;
use App\Jobs\SendReservationReminderJob;
use App\Models\Reservation;

class ReservationService
{
    public const MAX_TABLES_PER_SLOT = 6;

    public const TIME_SLOTS = [
        '12:00',
        '12:30',
        '13:00',
        '13:30',
        '19:00',
        '19:30',
        '20:00',
        '20:30',
    ];

    public function create(array $data): Reservation
    {
        $this->checkAvailability($data['location'], $data['date'], $data['time'], $data['guests']);

        $data['reference'] = generate_reference('RSV', \App\Models\Reservation::class);

        $reservation = Reservation::create($data);

        event(new ReservationCreated($reservation));

        SendReservationReminderJob::dispatch($reservation)
            ->delay($reservation->scheduled_at->copy()->subHours(2));

        return $reservation;
    }

    public function checkAvailability(string $location, string $date, string $time, int $guests): void
    {
        $count = Reservation::where('location', $location)
            ->where('date', $date)
            ->where('time', $time)
            ->whereNotIn('status', [ReservationStatus::CANCELLED->value, ReservationStatus::NO_SHOW->value])
            ->count();

        if ($count >= self::MAX_TABLES_PER_SLOT) {
            throw new SlotUnavailableException();
        }
    }

    public function getAvailability(string $location, string $date): array
    {
        return collect(self::TIME_SLOTS)->map(function (string $time) use ($location, $date) {
            $count = Reservation::where('location', $location)
                ->where('date', $date)
                ->where('time', $time)
                ->whereNotIn('status', [ReservationStatus::CANCELLED->value, ReservationStatus::NO_SHOW->value])
                ->count();

            $remaining = max(0, self::MAX_TABLES_PER_SLOT - $count);

            return [
                'time' => $time,
                'available' => $remaining > 0,
                'remaining' => $remaining,
            ];
        })->all();
    }

    public function confirm(Reservation $reservation): Reservation
    {
        $reservation->update([
            'status' => ReservationStatus::CONFIRMED->value,
            'confirmed_at' => now(),
        ]);

        event(new ReservationConfirmed($reservation->fresh()));

        return $reservation->fresh();
    }

    public function cancel(Reservation $reservation, ?string $reason = null): Reservation
    {
        $reservation->update([
            'status' => ReservationStatus::CANCELLED->value,
            'cancelled_at' => now(),
            'cancel_reason' => $reason,
        ]);

        event(new ReservationCancelled($reservation->fresh()));

        return $reservation->fresh();
    }
}
