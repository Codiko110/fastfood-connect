<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Revenue;
use App\Models\Delivery;

class StatisticsService
{
    public function getDashboardStats(): array
    {
        return [
            'total_orders' => Order::count(),
            'daily_revenue' => Order::whereDate('created_at', today())->sum('total'),
            'pending' => Order::where('status', 'pending')->count(),
            'preparing' => Order::where('status', 'preparing')->count(),
            'ready' => Order::where('status', 'ready')->count(),
            'deliveries_active' => Delivery::whereIn('status', ['assigned', 'in_transit'])->count(),
        ];
    }

    public function getWeeklySales(): array
    {
        $weeklySales = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weeklySales[$date->isoFormat('ddd')] = Order::whereDate('created_at', $date)->sum('total');
        }
        return $weeklySales;
    }

    public function getMonthlyRevenue(): array
    {
        $monthlyRevenue = [];
        for ($i = 0; $i < 7; $i++) {
            $date = now()->subMonths($i);
            $monthlyRevenue[$date->isoFormat('MMM')] = Order::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total');
        }
        return array_reverse($monthlyRevenue);
    }

    public function getFullStatistics(): array
    {
        $orderRevenue = Order::sum('total');
        $manualRevenue = Revenue::where('type', 'income')->sum('amount');
        $manualExpenses = Revenue::where('type', 'expense')->sum('amount');

        return [
            'totalRevenue' => $orderRevenue + $manualRevenue - $manualExpenses,
            'orderRevenue' => $orderRevenue,
            'manualRevenue' => $manualRevenue,
            'manualExpenses' => $manualExpenses,
            'averageBasket' => Order::avg('total') ?? 0,
            'averagePrep' => Product::avg('preparation_time') ?? 0,
            'monthlyRevenue' => $this->getMonthlyRevenue(),
            'topProducts' => Product::withCount('orderItems')
                ->orderBy('order_items_count', 'desc')
                ->take(5)
                ->get(),
            'categoryBreakdown' => Category::withCount('products')->orderBy('name')->get(),
            'revenueLogs' => Revenue::latest()->take(20)->get(),
        ];
    }

    public function storeRevenue(array $data): array|true
    {
        $amountAr = (float) $data['amount_ar'];
        $amountEur = $amountAr / 5000;

        if ($data['type'] === 'expense') {
            $orderRevenue = Order::sum('total');
            $manualRevenue = Revenue::where('type', 'income')->sum('amount');
            $manualExpenses = Revenue::where('type', 'expense')->sum('amount');
            $balanceEur = $orderRevenue + $manualRevenue - $manualExpenses;

            if ($amountEur > $balanceEur) {
                $balanceAr = (int) round($balanceEur * 5000);
                return [
                    'error' => "Solde insuffisant. Solde actuel : " . number_format($balanceAr, 0, ',', ' ') . ' Ar',
                ];
            }
        }

        Revenue::create([
            'amount' => $amountEur,
            'type' => $data['type'],
            'label' => $data['label'],
            'notes' => $data['notes'] ?? null,
        ]);

        return true;
    }
}
