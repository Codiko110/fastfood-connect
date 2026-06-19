<?php

namespace App\View\Composers;

use App\Models\Order;
use Illuminate\View\View;

class TableLayoutComposer
{
    public function compose(View $view): void
    {
        $tableId = session('table_id');
        $activeOrders = collect();
        $readyCount = 0;

        if ($tableId) {
            $activeOrders = Order::where('table_id', $tableId)
                ->whereNotIn('status', ['delivered', 'cancelled'])
                ->with('items.product')
                ->latest()
                ->get();

            $readyCount = $activeOrders->where('status', 'ready')->count();
        }

        $view->with('activeOrders', $activeOrders);
        $view->with('readyCount', $readyCount);
    }
}
