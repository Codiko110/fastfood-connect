<?php

namespace App\Events;

use App\Models\Table;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class TableStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public array $table;
    public array $stats;

    public function __construct(Table $table)
    {
        $this->table = [
            'id' => $table->id,
            'table_number' => $table->table_number,
            'status' => $table->status,
            'capacity' => $table->capacity,
        ];

        $freeCount = Table::where('status', 'free')->count();
        $occupiedCount = Table::where('status', 'occupied')->count();
        $orderingCount = Table::where('status', 'ordering')->count();

        $this->stats = [
            'free' => $freeCount,
            'occupied' => $occupiedCount,
            'ordering' => $orderingCount,
            'total' => $freeCount + $occupiedCount + $orderingCount,
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('tables'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'table.updated';
    }
}
