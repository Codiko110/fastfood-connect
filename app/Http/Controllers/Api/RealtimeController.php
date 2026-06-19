<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Delivery;
use App\Services\StatisticsService;
use Illuminate\Http\JsonResponse;

class RealtimeController extends Controller
{
    public function __construct(
        private StatisticsService $statisticsService,
    ) {}

    public function tableOrders(): JsonResponse
    {
        $tableId = session('table_id');
        if (!$tableId) {
            return response()->json(['orders' => []]);
        }

        $orders = Order::where('table_id', $tableId)
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->with('items.product')
            ->latest()
            ->get()
            ->map(fn($order) => $this->mapOrder($order, [
                'pending' => 'En attente',
                'confirmed' => 'Confirmée',
                'preparing' => 'En préparation',
                'ready' => 'Prête !',
                'delivered' => 'Livrée',
                'cancelled' => 'Annulée',
            ]));

        return response()->json(['orders' => $orders]);
    }

    public function adminOrders(): JsonResponse
    {
        $orders = Order::with('items.product', 'table')
            ->orderByRaw("CASE status
                WHEN 'pending' THEN 1
                WHEN 'confirmed' THEN 2
                WHEN 'preparing' THEN 3
                WHEN 'ready' THEN 4
                WHEN 'delivered' THEN 5
                WHEN 'cancelled' THEN 6
                ELSE 7 END")
            ->latest()
            ->take(20)
            ->get()
            ->map(fn($order) => $this->mapOrder($order, [
                'pending' => 'Nouvelle',
                'confirmed' => 'Confirmée',
                'preparing' => 'En préparation',
                'ready' => 'Prête',
                'delivered' => 'Livrée',
                'cancelled' => 'Annulée',
            ]));

        $stats = $this->statisticsService->getDashboardStats();

        return response()->json(['orders' => $orders, 'stats' => $stats]);
    }

    public function orderStatus(Order $order): JsonResponse
    {
        $order->load('statuses');

        return response()->json([
            'id' => $order->id,
            'status' => $order->status,
            'status_label' => match ($order->status) {
                'pending' => 'Nouvelle',
                'confirmed' => 'Confirmée',
                'preparing' => 'En préparation',
                'ready' => 'Prête',
                'delivered' => 'Livrée',
                'cancelled' => 'Annulée',
                default => $order->status,
            },
            'statuses' => $order->statuses->map(fn($status) => [
                'status' => $status->status,
                'label' => match ($status->status) {
                    'pending' => 'Commande envoyée',
                    'confirmed' => 'Confirmée',
                    'preparing' => 'En préparation',
                    'ready' => 'Prête à servir',
                    'delivered' => 'Servie',
                    'cancelled' => 'Annulée',
                    default => $status->status,
                },
                'time' => $status->created_at?->format('H:i'),
            ]),
            'items_count' => $order->items->count(),
            'total' => price($order->total),
        ]);
    }

    public function dashboardStats(): JsonResponse
    {
        $stats = $this->statisticsService->getDashboardStats();
        $stats['daily_revenue'] = price($stats['daily_revenue']);

        return response()->json(['stats' => $stats]);
    }

    private function mapOrder(Order $order, array $statusLabels): array
    {
        return [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status,
            'total' => price($order->total),
            'items_count' => $order->items->count(),
            'customer_name' => $order->customer_name ?? 'Anonyme',
            'customer_phone' => $order->customer_phone,
            'type' => $order->type,
            'table_number' => $order->table?->table_number,
            'created_at' => $order->created_at->diffForHumans(),
            'status_label' => $statusLabels[$order->status] ?? $order->status,
        ];
    }
}
