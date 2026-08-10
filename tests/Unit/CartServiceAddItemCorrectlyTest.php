<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartServiceAddItemCorrectlyTest extends TestCase
{
    use RefreshDatabase;

    public function test_adding_item_stores_correct_data_in_cart(): void
    {
        $product = Product::factory()->create(['price' => 1500, 'is_active' => true]);
        $cart = new CartService();

        $cart->add($product->id, 2);

        $items = $cart->get();
        $this->assertCount(1, $items);
        $item = array_values($items)[0];
        $this->assertEquals($product->id, $item['product_id']);
        $this->assertEquals(2, $item['qty']);
        $this->assertEquals(1500, $item['price']);
    }

    public function test_adding_same_product_twice_increments_quantity(): void
    {
        $product = Product::factory()->create(['price' => 1000, 'is_active' => true]);
        $cart = new CartService();

        $cart->add($product->id, 1);
        $cart->add($product->id, 3);

        $items = $cart->get();
        $this->assertCount(1, $items);
        $this->assertEquals(4, array_values($items)[0]['qty']);
    }
}