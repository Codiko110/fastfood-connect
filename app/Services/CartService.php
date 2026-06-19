<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CartService
{
    public function getCart(): Cart
    {
        $tableId = session('table_id');
        if ($tableId) {
            return Cart::firstOrCreate(['table_id' => $tableId]);
        }

        if (auth()->check()) {
            return Cart::firstOrCreate(['user_id' => auth()->id()]);
        }

        return Cart::firstOrCreate(['session_id' => session()->getId()]);
    }

    public function addProduct(Cart $cart, Product $product, array $extras = [], int $quantity = 1): void
    {
        $extrasPrice = $this->calculateExtrasPrice($extras);
        $unitPrice = $product->price + $extrasPrice;

        $existingItem = $cart->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            $existingItem->increment('quantity', $quantity);
            $existingItem->update(['total_price' => $existingItem->quantity * $existingItem->unit_price]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'extras' => $extras,
                'unit_price' => $unitPrice,
                'total_price' => $unitPrice * $quantity,
            ]);
        }

        session(['cart_count' => $cart->items()->sum('quantity')]);
    }

    public function updateItemQuantity(CartItem $item, int $quantity): void
    {
        $item->update([
            'quantity' => $quantity,
            'total_price' => $quantity * $item->unit_price,
        ]);

        $cart = $item->cart;
        session(['cart_count' => $cart->items()->sum('quantity')]);
    }

    public function removeItem(CartItem $item): void
    {
        $cart = $item->cart;
        $item->delete();
        session(['cart_count' => $cart->items()->sum('quantity')]);
    }

    public function clearCart(Cart $cart): void
    {
        $cart->items()->delete();
        session(['cart_count' => 0]);
    }

    private function calculateExtrasPrice(array $extras): float
    {
        if (empty($extras)) {
            return 0;
        }

        return \App\Models\ProductExtra::whereIn('id', $extras)->sum('price');
    }
}
