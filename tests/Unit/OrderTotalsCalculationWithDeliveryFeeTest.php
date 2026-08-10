<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTotalsCalculationWithDeliveryFeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_totals_include_delivery_fee_when_cart_is_not_empty(): void
    {
        $product = Product::factory()->create(['price' => 2000, 'is_active' => true]);
        $cart = new CartService();
        $cart->add($product->id, 1);

        $totals = (new OrderService())->calculateTotals($cart);

        $this->assertEquals(2000, $totals['subtotal']);
        $this->assertEquals(500, $totals['deliveryFee']);
        $this->assertEquals(2500, $totals['total']);
    }

    public function test_delivery_fee_is_zero_when_cart_is_empty(): void
    {
        $cart = new CartService();

        $totals = (new OrderService())->calculateTotals($cart);

        $this->assertEquals(0, $totals['subtotal']);
        $this->assertEquals(0, $totals['deliveryFee']);
        $this->assertEquals(0, $totals['total']);
    }
}