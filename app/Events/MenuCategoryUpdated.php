<?php

namespace App\Events;

use App\Models\Category;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class MenuCategoryUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public array $category;

    public function __construct(Category $category, string $action = 'updated')
    {
        $this->category = [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
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
        return 'category.updated';
    }
}
