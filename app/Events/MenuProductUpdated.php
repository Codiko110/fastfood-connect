<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class MenuProductUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public array $product;

    public function __construct(Product $product, string $action = 'updated')
    {
        $this->product = [
            'id' => $product->id,
            'name' => $product->name,
            'is_available' => $product->is_available,
            'price' => $product->price,
            'category_id' => $product->category_id,
            'category_name' => $product->category?->name,
            'action' => $action,
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('menu'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'product.updated';
    }
}
