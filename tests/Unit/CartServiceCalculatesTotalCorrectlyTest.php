<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartServiceCalculatesTotalCorrectlyTest extends TestCase
{
    use RefreshDatabase;

    public function test_total_sums_price_times_quantity_for_all_items(): void
    {
        $productA = Product::factory()->create(['price' => 1000, 'is_active' => true]);
        $productB = Product::factory()->create(['price' => 500, 'is_active' => true]);
        $cart = new CartService();

        $cart->add($productA->id, 2); // 2000
        $cart->add($productB->id, 3); // 1500

        $this->assertEquals(3500, $cart->total());
    }

    public function test_total_is_zero_for_empty_cart(): void
    {
        $cart = new CartService();

        $this->assertEquals(0, $cart->total());
    }
}