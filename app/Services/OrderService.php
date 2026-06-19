<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Delivery;
use App\Events\OrderStatusUpdated;

class OrderService
{
    public function createFromCart(Cart $cart, array $data): Order
    {
        $cart->load('items.product');

        $subtotal = $cart->items->sum('total_price');
        $deliveryFee = $data['delivery_fee'] ?? 0;
        $total = $subtotal + $deliveryFee;

        $order = Order::create([
            'order_number' => generateOrderNumber($data['type']),
            'user_id' => auth()->id(),
            'table_id' => $data['table_id'] ?? null,
            'customer_name' => $data['customer_name'] ?? null,
            'customer_phone' => $data['customer_phone'] ?? null,
            'customer_address' => $data['customer_address'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'type' => $data['type'],
            'status' => 'pending',
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total' => $total,
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'extras' => $item->extras,
                'total_price' => $item->total_price,
            ]);
        }

        $this->addStatus($order, 'pending', $data['type'] === 'table'
            ? 'Commande reçue depuis la table'
            : 'Commande reçue');

        return $order;
    }

    public function addStatus(Order $order, string $status, ?string $notes = null): void
    {
        $order->statuses()->create([
            'status' => $status,
            'notes' => $notes,
        ]);
    }

    public function addPayment(Order $order, string $method, string $status = 'completed'): void
    {
        $order->payment()->create([
            'method' => $method,
            'status' => $status,
            'amount' => $order->total,
        ]);
    }

    public function broadcastUpdate(Order $order): void
    {
        broadcastSafe(new OrderStatusUpdated($order));
    }

    public function getStatusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Nouvelle',
            'confirmed' => 'Confirmée',
            'preparing' => 'En préparation',
            'ready' => 'Prête',
            'delivered' => 'Livrée',
            'cancelled' => 'Annulée',
            default => $status,
        };
    }

    public function getStatusLabels(): array
    {
        return [
            'pending' => 'Nouvelle',
            'confirmed' => 'Confirmée',
            'preparing' => 'En préparation',
            'ready' => 'Prête',
            'delivered' => 'Livrée',
            'cancelled' => 'Annulée',
        ];
    }

    public function getStatusFlowLabels(): array
    {
        return [
            'pending' => 'Commande envoyée',
            'confirmed' => 'Confirmée',
            'preparing' => 'En préparation',
            'ready' => 'Prête à servir',
            'delivered' => 'Servie',
            'cancelled' => 'Annulée',
        ];
    }

    public function formatStatusFlow($statuses): array
    {
        $labels = $this->getStatusFlowLabels();

        return $statuses->map(fn($s) => [
            'status' => $s->status,
            'label' => $labels[$s->status] ?? $s->status,
            'time' => $s->created_at?->format('H:i'),
        ])->values()->toArray();
    }

    public function getDeliveryStatusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'En attente',
            'assigned' => 'Assignée',
            'in_transit' => 'En cours',
            'delivered' => 'Livrée',
            'failed' => 'Échouée',
            default => $status,
        };
    }

    public function getTimelineLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Commande envoyée',
            'confirmed' => 'Confirmée',
            'preparing' => 'En préparation',
            'ready' => 'Prête à servir',
            'delivered' => 'Servie',
            'cancelled' => 'Annulée',
            default => $status,
        };
    }

    public function getOrderStatusPriority(string $status): int
    {
        return match ($status) {
            'pending' => 1,
            'confirmed' => 2,
            'preparing' => 3,
            'ready' => 4,
            'delivered' => 5,
            'cancelled' => 6,
            default => 7,
        };
    }
}
