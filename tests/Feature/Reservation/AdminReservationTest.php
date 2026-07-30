<?php

namespace Tests\Feature\Reservation;

use App\Models\Reservation;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReservationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    protected function admin(): User
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        return $admin;
    }

    public function test_admin_can_confirm_reservation(): void
    {
        $reservation = Reservation::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($this->admin())->patch(
            route('admin.reservations.updateStatus', $reservation),
            ['status' => 'confirmed']
        );

        $response->assertRedirect();
        $this->assertSame('confirmed', $reservation->fresh()->status->value);
        $this->assertNotNull($reservation->fresh()->confirmed_at);
    }

    public function test_admin_can_cancel_reservation(): void
    {
        $reservation = Reservation::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($this->admin())->patch(
            route('admin.reservations.updateStatus', $reservation),
            ['status' => 'cancelled', 'cancel_reason' => 'Client indisponible']
        );

        $response->assertRedirect();
        $this->assertSame('cancelled', $reservation->fresh()->status->value);
        $this->assertSame('Client indisponible', $reservation->fresh()->cancel_reason);
    }
}