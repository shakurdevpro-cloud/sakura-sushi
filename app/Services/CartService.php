<?php
// app/Services/CartService.php
namespace App\Services;

use App\Models\Product;

class CartService
{
    private const SESSION_KEY = 'cart';

    public function add(int $productId, int $qty = 1, array $options = []): void
    {
        $cart = $this->get();
        $product = Product::active()->findOrFail($productId);
        $key = $productId . '_' . md5(serialize($options));

        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $qty;
        } else {
            $cart[$key] = [
                'product_id' => $productId,
                'name' => $product->name,
                'price' => $product->price,
                'qty' => $qty,
                'options' => $options,
            ];
        }

        session([self::SESSION_KEY => $cart]);
    }

    public function remove(string $key): void
    {
        $cart = $this->get();
        unset($cart[$key]);
        session([self::SESSION_KEY => $cart]);
    }

    public function update(string $key, int $qty): void
    {
        $cart = $this->get();

        if (! isset($cart[$key])) {
            return;
        }

        if ($qty <= 0) {
            unset($cart[$key]);
        } else {
            $cart[$key]['qty'] = $qty;
        }

        session([self::SESSION_KEY => $cart]);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function get(): array
    {
        return session(self::SESSION_KEY, []);
    }

    public function total(): int
    {
        return collect($this->get())->sum(fn($i) => $i['price'] * $i['qty']);
    }
}
