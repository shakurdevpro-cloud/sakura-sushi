<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartIsClearedAfterOrderCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_is_empty_after_order_is_placed(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 800, 'is_active' => true]);

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/cart', ['product_id' => $product->id, 'qty' => 1])
            ->assertStatus(201);

        $this->actingAs($user, 'sanctum')->postJson('/api/v1/orders', [
            'type' => 'pickup',
        ])->assertStatus(200);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/cart');

        $response->assertStatus(200)->assertJsonPath('items', []);
    }
}