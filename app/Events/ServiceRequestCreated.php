<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class ServiceRequestCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public array $request;

    public function __construct(int $tableId, string $tableNumber, string $type)
    {
        $this->request = [
            'table_id' => $tableId,
            'table_number' => $tableNumber,
            'type' => $type,
            'type_label' => match($type) {
                'serveur' => 'Serveur',
                'eau' => 'Eau',
                'assistance' => 'Assistance',
                'bill' => 'Addition',
                default => $type,
            },
            'created_at' => now()->format('H:i'),
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('table.' . $this->request['table_id']),
            new Channel('orders'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'service.requested';
    }
}
