<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);

        return [
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'name' => ucfirst($name),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(500, 8000),
            'is_active' => true,
            'is_featured' => $this->faker->boolean(20),
            'tags' => $this->faker->randomElements(['vegan', 'spicy', 'raw'], 2),
            'stock' => -1,
        ];
    }
}
