<?php
// app/Http/Controllers/Admin/ReservationController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateReservationStatusRequest;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(protected ReservationService $reservationService) {}

    public function index(Request $request)
    {
        $reservations = Reservation::query()
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->location, fn($q, $l) => $q->where('location', $l))
            ->when($request->date, fn($q, $d) => $q->where('date', $d))
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->paginate(20);

        return view('admin.reservations.index', compact('reservations'));
    }

    public function show(Reservation $reservation)
    {
        return view('admin.reservations.show', compact('reservation'));
    }

    public function updateStatus(UpdateReservationStatusRequest $request, Reservation $reservation)
    {
        match ($request->status) {
            'confirmed' => $this->reservationService->confirm($reservation),
            'cancelled' => $this->reservationService->cancel($reservation, $request->cancel_reason),
            'no_show' => $reservation->update(['status' => \App\Enums\ReservationStatus::NO_SHOW->value]),
        };

        return redirect()->route('admin.reservations.show', $reservation)->with('status', 'Statut mis à jour.');
    }
}
