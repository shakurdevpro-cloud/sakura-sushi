<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerCanCreateOrderFromCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_create_order_from_cart(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 1000, 'is_active' => true]);

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/cart', ['product_id' => $product->id, 'qty' => 2])
            ->assertStatus(201);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/orders', [
            'type' => 'delivery',
            'delivery_address' => [
                'street' => '123 rue Sushi',
                'city' => 'Montréal',
                'zip' => 'H1A 1A1',
            ],
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', ['user_id' => $user->id, 'total' => 2500]);
        $this->assertDatabaseCount('order_items', 1);
    }
}