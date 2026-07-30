<?php
// app/Http/Controllers/Api/V1/ReservationController.php
namespace App\Http\Controllers\Api\V1;

use App\Exceptions\SlotUnavailableException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreReservationRequest;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(protected ReservationService $reservationService)
    {
    }

    public function store(StoreReservationRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth('sanctum')->id();

        try {
            $reservation = $this->reservationService->create($data);
        } catch (SlotUnavailableException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json($reservation, 201);
    }

    public function availability(Request $request)
    {
        $request->validate([
            'location' => ['required', 'in:downtown,midtown'],
            'date' => ['required', 'date'],
        ]);

        return response()->json(
            $this->reservationService->getAvailability($request->location, $request->date)
        );
    }
}