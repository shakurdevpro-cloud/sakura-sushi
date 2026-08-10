<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = $this->faker->numberBetween(1000, 8000);
        $deliveryFee = $this->faker->randomElement([0, 300, 500]);

        return [
            'reference' => generate_reference('SKR', Order::class),
            'status' => OrderStatus::PENDING->value,
            'type' => $this->faker->randomElement(['delivery', 'pickup']),
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'discount' => 0,
            'total' => $subtotal + $deliveryFee,
            'ip_address' => $this->faker->ipv4(),
        ];
    }
}