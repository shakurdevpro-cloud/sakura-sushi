<?php
// tests/Feature/Auth/OrderPolicyTest.php
namespace Tests\Feature\Auth;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_policy_prevents_unauthorized_order_cancellation(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $order = Order::create([
            'user_id' => $owner->id,
            'status' => OrderStatus::PENDING->value,
            'total_amount' => 1500,
        ]);

        $this->assertTrue($owner->can('cancel', $order));
        $this->assertFalse($otherUser->can('cancel', $order));
    }
}   