<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('reservations.view');
    }

    public function view(User $user, Reservation $reservation): bool
    {
        return $user->id === $reservation->user_id || $user->hasPermissionTo('reservations.view');
    }

    public function confirm(User $user, Reservation $reservation): bool
    {
        return $user->hasPermissionTo('reservations.confirm');
    }

    public function cancel(User $user, Reservation $reservation): bool
    {
        return $user->id === $reservation->user_id || $user->hasPermissionTo('reservations.cancel');
    }
}