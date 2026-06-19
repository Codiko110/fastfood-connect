<?php

namespace App\Events;

use App\Models\Delivery;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class DeliveryStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public array $delivery;

    public function __construct(Delivery $delivery)
    {
        $delivery->load('order');

        $this->delivery = [
            'id' => $delivery->id,
            'order_id' => $delivery->order_id,
            'order_number' => $delivery->order?->order_number,
            'delivery_person_name' => $delivery->delivery_person_name,
            'status' => $delivery->status,
            'status_label' => match($delivery->status) {
                'pending' => 'En attente',
                'assigned' => 'Assignée',
                'in_transit' => 'En cours',
                'delivered' => 'Livrée',
                'failed' => 'Échouée',
                default => $delivery->status,
            },
            'estimated_time' => $delivery->estimated_time,
            'delivered_at' => $delivery->delivered_at?->format('H:i'),
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('deliveries'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'delivery.updated';
    }
}
