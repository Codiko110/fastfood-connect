<?php

const MGA_CONVERSION_RATE = 5000;

if (!function_exists('price')) {
    function price(float $amount, bool $convert = true): string
    {
        $value = $convert ? $amount * MGA_CONVERSION_RATE : $amount;
        return number_format($value, 0, ',', ' ') . ' Ar';
    }
}

if (!function_exists('mgaToEuro')) {
    function mgaToEuro(float $amount): float
    {
        return $amount / MGA_CONVERSION_RATE;
    }
}

if (!function_exists('euroToMga')) {
    function euroToMga(float $amount): float
    {
        return $amount * MGA_CONVERSION_RATE;
    }
}

if (!function_exists('generateOrderNumber')) {
    function generateOrderNumber(string $type): string
    {
        $prefix = $type === 'table' ? 'CT' : 'CM';
        $date = now()->format('j-n-y');
        $count = \App\Models\Order::where('type', $type)->whereDate('created_at', today())->count() + 1;
        return $prefix . '_' . $date . '-' . $count;
    }
}

if (!function_exists('broadcastSafe')) {
    function broadcastSafe($event): void
    {
        try {
            event($event);
        } catch (\Throwable $e) {
            // Reverb server may not be running
        }
    }
}

if (!function_exists('productImageUrl')) {
    function productImageUrl($product): string
    {
        if (!$product || !$product->image) {
            return 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&h=300&fit=crop';
        }
        if (str_starts_with($product->image, 'http')) {
            return $product->image;
        }
        return asset('storage/' . $product->image);
    }
}
