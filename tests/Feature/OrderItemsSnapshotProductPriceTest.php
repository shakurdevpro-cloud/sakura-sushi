<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderItemsSnapshotProductPriceTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_item_keeps_price_at_time_of_adding_to_cart(): void
    {
        $product = Product::factory()->create(['price' => 1000, 'is_active' => true]);
        $cart = new CartService();
        $cart->add($product->id, 1);

        $product->update(['price' => 5000]);

        $order = (new OrderService())->createFromCart([], $cart);

        $this->assertEquals(1000, $order->items->first()->price);
    }
}