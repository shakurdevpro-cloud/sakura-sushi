<?php

namespace Tests\Unit;

use App\Exceptions\SlotUnavailableException;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_slot_availability_checks_capacity_correctly(): void
    {
        $service = app(ReservationService::class);

        Reservation::factory()->count(6)->create([
            'location' => 'downtown',
            'date' => '2026-08-15',
            'time' => '19:00',
        ]);

        $count = Reservation::where('location', 'downtown')
            ->where('date', '2026-08-15')
            ->where('time', '19:00')
            ->count();

        $this->expectException(SlotUnavailableException::class);

        $service->checkAvailability('downtown', '2026-08-15', '19:00', 2);
    }

    public function test_reservation_reference_is_unique(): void
    {
        $references = collect(range(1, 20))->map(fn() => generate_reference('RSV'));

        $this->assertSame($references->count(), $references->unique()->count());
    }
}
