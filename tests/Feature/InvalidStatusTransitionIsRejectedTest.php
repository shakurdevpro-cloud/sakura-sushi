<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Exceptions\InvalidOrderTransitionException;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvalidStatusTransitionIsRejectedTest extends TestCase
{
    use RefreshDatabase;

    public function test_transition_from_delivered_to_confirmed_is_rejected(): void
    {
        $order = Order::factory()->create(['status' => OrderStatus::DELIVERED->value]);

        $this->expectException(InvalidOrderTransitionException::class);

        (new OrderService())->transitionStatus($order, OrderStatus::CONFIRMED->value);
    }
}