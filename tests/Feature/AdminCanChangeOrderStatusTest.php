<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;


class AdminCanChangeOrderStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_transition_order_status(): void
    {
        $admin = User::factory()->create();
        Role::findOrCreate('admin');
        $admin->assignRole('admin');
        $order = Order::factory()->create(['status' => OrderStatus::PENDING->value]);

        $response = $this->actingAs($admin)->patch("/admin/orders/{$order->id}/status", [
            'status' => 'confirmed',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::CONFIRMED->value,
        ]);
    }
}
