<?php

namespace App\Policies;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('orders.view');
    }

    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->user_id || $user->hasPermissionTo('orders.view');
    }

    public function cancel(User $user, Order $order): bool
    {
        return $user->id === $order->user_id
            && $order->status === OrderStatus::PENDING
            && $order->created_at->gt(now()->subHours(1));
    }

    public function refund(User $user, Order $order): bool
    {
        return $user->hasPermissionTo('orders.refund');
    }
}