<?php

namespace Database\Factories;

use App\Enums\ReservationStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'reference' => generate_reference('RSV'),
            'location' => $this->faker->randomElement(['downtown', 'midtown']),
            'date' => $this->faker->dateTimeBetween('+1 day', '+30 days')->format('Y-m-d'),
            'time' => $this->faker->randomElement(['12:00', '12:30', '19:00', '19:30', '20:00']),
            'guests' => $this->faker->numberBetween(1, 8),
            'status' => ReservationStatus::PENDING->value,
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->numerify('06########'),
            'sms_consent' => $this->faker->boolean(70),
            'sms_reminder_sent' => false,
        ];
    }
}