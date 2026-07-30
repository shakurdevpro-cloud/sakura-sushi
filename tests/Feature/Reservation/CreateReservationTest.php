<?php

namespace Tests\Feature\Reservation;

use App\Jobs\SendReservationReminderJob;
use App\Models\Reservation;
use App\Notifications\Reservation\ReservationConfirmedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CreateReservationTest extends TestCase
{
    use RefreshDatabase;

    protected function payload(array $overrides = []): array
    {
        return array_merge([
            'location' => 'downtown',
            'date' => now()->addDays(3)->toDateString(),
            'time' => '19:00',
            'guests' => 4,
            'first_name' => 'Jean',
            'last_name' => 'Dupont',
            'email' => 'jean@example.com',
            'phone' => '0612345678',
            'sms_consent' => true,
        ], $overrides);
    }

    public function test_customer_can_create_reservation(): void
    {
        $response = $this->postJson('/api/v1/reservations', $this->payload());

        $response->assertStatus(201);
        $this->assertDatabaseHas('reservations', ['email' => 'jean@example.com', 'guests' => 4]);
    }

    public function test_reservation_on_full_slot_throws_exception(): void
    {
        $date = now()->addDays(3)->toDateString();

        Reservation::factory()->count(6)->create([
            'location' => 'downtown',
            'date' => $date,
            'time' => '19:00',
        ]);

        $response = $this->postJson('/api/v1/reservations', $this->payload(['date' => $date]));

        $response->assertStatus(422);
    }

    public function test_email_is_sent_on_reservation_created(): void
    {
        Notification::fake();

        $this->postJson('/api/v1/reservations', $this->payload())->assertStatus(201);

        $reservation = Reservation::first();

        Notification::assertSentTo($reservation, ReservationConfirmedNotification::class);
    }

    public function test_reminder_job_is_scheduled_with_correct_delay(): void
    {
        Queue::fake();

        $this->postJson('/api/v1/reservations', $this->payload())->assertStatus(201);

        $reservation = Reservation::first();
        $expectedDelay = $reservation->scheduled_at->copy()->subHours(2);

        Queue::assertPushed(SendReservationReminderJob::class, function ($job) use ($expectedDelay) {
            return $job->delay !== null
                && $job->delay->format('Y-m-d H:i') === $expectedDelay->format('Y-m-d H:i');
        });
    }
}