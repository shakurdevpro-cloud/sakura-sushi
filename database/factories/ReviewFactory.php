<?php

namespace Database\Factories;

use App\Enums\ReviewStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'rating' => $this->faker->numberBetween(1, 5),
            'title' => $this->faker->sentence(4),
            'body' => $this->faker->paragraph(),
            'status' => ReviewStatus::PENDING->value,
            'source' => 'site',
        ];
    }

    public function approved(): static
    {
        return $this->state(fn() => [
            'status' => ReviewStatus::APPROVED->value,
            'approved_at' => now(),
        ]);
    }
}
