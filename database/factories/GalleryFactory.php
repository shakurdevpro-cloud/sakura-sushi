<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GalleryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->optional()->paragraph(),
            'category' => $this->faker->randomElement(['dishes', 'interior', 'kitchen', 'events']),
            'path' => 'gallery/placeholder.jpg',
            'alt' => $this->faker->sentence(4),
            'sort_order' => 0,
            'is_active' => true,
        ];
    }
}