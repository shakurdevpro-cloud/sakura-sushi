<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Events\OrderCancelled;
use App\Events\OrderConfirmed;
use App\Events\OrderPlaced;
use App\Exceptions\InvalidOrderTransitionException;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderService
{
    private const TRANSITIONS = [
        'pending' => ['confirmed', 'cancelled'],
        'confirmed' => ['preparing', 'cancelled'],
        'preparing' => ['ready', 'cancelled'],
        'ready' => ['delivered', 'cancelled'],
        'delivered' => [],
        'cancelled' => [],
    ];

    public function createFromCart(array $data, CartService $cart): Order
    {
        return DB::transaction(function () use ($data, $cart) {
            $totals = $this->calculateTotals($cart, $data['promo_code'] ?? null);

            $order = Order::create([
                'user_id' => auth('sanctum')->id(),
                'reference' => generate_reference('SKR', Order::class),
                'status' => OrderStatus::PENDING->value,
                'type' => $data['type'] ?? 'delivery',
                'subtotal' => $totals['subtotal'],
                'delivery_fee' => $totals['deliveryFee'],
                'discount' => $totals['discount'],
                'total' => $totals['total'],
                'promo_code_id' => $totals['promoCodeId'],
                'delivery_address' => $data['delivery_address'] ?? null,
                'guest_email' => $data['guest_email'] ?? null,
                'guest_phone' => $data['guest_phone'] ?? null,
                'notes' => $data['notes'] ?? null,
                'ip_address' => request()->ip(),
            ]);

            foreach ($cart->get() as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['qty'],
                    'subtotal' => $item['price'] * $item['qty'],
                    'options' => $item['options'] ?? [],
                ]);
            }

            $cart->clear();

            event(new OrderPlaced($order));

            return $order->fresh('items');
        });
    }

    public function calculateTotals(CartService $cart, ?string $promoCode = null): array
    {
        $subtotal = $cart->total();
        $deliveryFee = $subtotal > 0 ? 500 : 0; // frais fixes 5$ — logique zone/livraison à affiner en Phase Promotions
        $discount = 0;
        $promoCodeId = null;

        // Validation/application du code promo branchée quand la table promo_codes existera

        $total = max(0, $subtotal + $deliveryFee - $discount);

        return [
            'subtotal' => $subtotal,
            'deliveryFee' => $deliveryFee,
            'discount' => $discount,
            'total' => $total,
            'promoCodeId' => $promoCodeId,
        ];
    }

    public function transitionStatus(Order $order, string $newStatus, ?string $cancelReason = null): Order
    {
        $currentStatus = $order->status->value;
        $allowed = self::TRANSITIONS[$currentStatus] ?? [];

        if (! in_array($newStatus, $allowed, true)) {
            throw new InvalidOrderTransitionException(
                "Transition de '{$currentStatus}' vers '{$newStatus}' non autorisée."
            );
        }

        $updates = ['status' => $newStatus];

        if ($newStatus === OrderStatus::CANCELLED->value) {
            $updates['cancelled_at'] = now();
            $updates['cancel_reason'] = $cancelReason;
        }

        $order->update($updates);

        match ($newStatus) {
            OrderStatus::CONFIRMED->value => event(new OrderConfirmed($order->fresh())),
            OrderStatus::CANCELLED->value => event(new OrderCancelled($order->fresh())),
            default => null,
        };

        return $order->fresh();
    }
}