<?php

namespace App\Events;

use App\Models\Order;
use App\Models\Delivery;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class OrderStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public array $order;
    public array $stats;
    public ?int $tableId;

    public function __construct(Order $order)
    {
        $order->load('items.product', 'statuses', 'table');

        $this->order = [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status,
            'status_label' => match($order->status) {
                'pending' => 'Nouvelle',
                'confirmed' => 'Confirmée',
                'preparing' => 'En préparation',
                'ready' => 'Prête !',
                'delivered' => 'Livrée',
                'cancelled' => 'Annulée',
                default => $order->status,
            },
            'total' => price($order->total),
            'items_count' => $order->items->count(),
            'customer_name' => $order->customer_name ?? 'Anonyme',
            'customer_phone' => $order->customer_phone,
            'type' => $order->type,
            'table_id' => $order->table_id,
            'table_number' => $order->table?->table_number,
            'created_at' => $order->created_at->diffForHumans(),
            'statuses' => $order->statuses->map(fn($s) => [
                'status' => $s->status,
                'label' => match($s->status) {
                    'pending' => 'Commande envoyée',
                    'confirmed' => 'Confirmée',
                    'preparing' => 'En préparation',
                    'ready' => 'Prête à servir',
                    'delivered' => 'Servie',
                    'cancelled' => 'Annulée',
                    default => $s->status,
                },
                'time' => $s->created_at?->format('H:i'),
            ]),
        ];

        $this->stats = [
            'total_orders' => Order::count(),
            'daily_revenue' => price(Order::whereDate('created_at', today())->sum('total')),
            'pending' => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'preparing' => Order::where('status', 'preparing')->count(),
            'ready' => Order::where('status', 'ready')->count(),
            'deliveries_active' => Delivery::whereIn('status', ['assigned', 'in_transit'])->count(),
        ];

        $this->tableId = $order->table_id;
    }

    public function broadcastOn(): array
    {
        $channels = [
            new Channel('orders'),
        ];

        if ($this->tableId) {
            $channels[] = new Channel('table.' . $this->tableId);
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'order.updated';
    }
}
