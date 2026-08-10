<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {
    }

    public function store(StoreOrderRequest $request, CartService $cart)
    {
        if (empty($cart->get())) {
            return response()->json(['message' => 'Votre panier est vide.'], 422);
        }

        $order = $this->orderService->createFromCart($request->validated(), $cart);

        return new OrderResource($order);
    }

    public function show(Order $order)
    {
        return new OrderResource($order->load('items'));
    }

    public function cancel(Order $order)
    {
        $this->authorize('cancel', $order);

        $order = $this->orderService->transitionStatus($order, 'cancelled', 'Annulée par le client');

        return new OrderResource($order->load('items'));
    }
}