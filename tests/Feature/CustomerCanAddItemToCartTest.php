<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerCanAddItemToCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_add_product_to_cart(): void
    {
        $product = Product::factory()->create(['price' => 1200, 'is_active' => true]);

        $response = $this->postJson('/api/v1/cart', [
            'product_id' => $product->id,
            'qty' => 2,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('total', 2400);
    }
}