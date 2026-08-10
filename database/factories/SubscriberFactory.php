<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SubscriberFactory extends Factory
{
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'name' => $this->faker->name(),
            'token' => Str::random(64),
            'source' => $this->faker->randomElement(['homepage', 'checkout', 'rewards']),
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn () => ['confirmed_at' => now()]);
    }

    public function unsubscribed(): static
    {
        return $this->state(fn () => ['unsubscribed_at' => now()]);
    }
}