<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService)
    {
    }

    public function index()
    {
        return response()->json([
            'items' => $this->cartService->get(),
            'total' => $this->cartService->total(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'qty' => ['nullable', 'integer', 'min:1'],
            'options' => ['nullable', 'array'],
        ]);

        $this->cartService->add($data['product_id'], $data['qty'] ?? 1, $data['options'] ?? []);

        return response()->json([
            'items' => $this->cartService->get(),
            'total' => $this->cartService->total(),
        ], 201);
    }

    public function update(Request $request, string $key)
    {
        $data = $request->validate(['qty' => ['required', 'integer']]);

        $this->cartService->update($key, $data['qty']);

        return response()->json([
            'items' => $this->cartService->get(),
            'total' => $this->cartService->total(),
        ]);
    }

    public function destroy(string $key)
    {
        $this->cartService->remove($key);

        return response()->json([
            'items' => $this->cartService->get(),
            'total' => $this->cartService->total(),
        ]);
    }

    public function clear()
    {
        $this->cartService->clear();

        return response()->json(['message' => 'Panier vidé.']);
    }
}