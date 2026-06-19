<?php

namespace App\Services;

use App\Models\Table;
use App\Models\Order;
use App\Events\TableStatusUpdated;
use App\Events\ServiceRequestCreated;

class TableService
{
    public function getAvailableTables()
    {
        return Table::where('status', 'free')->orderBy('table_number')->get();
    }

    public function selectTable(int $tableId): bool
    {
        $updated = Table::where('id', $tableId)
            ->where('status', 'free')
            ->update(['status' => 'ordering']);

        if (!$updated) {
            return false;
        }

        $table = Table::findOrFail($tableId);
        session(['table_id' => $table->id]);
        session(['table_number' => $table->table_number]);
        session(['session_started_at' => now()->toDateTimeString()]);

        broadcastSafe(new TableStatusUpdated($table));

        return true;
    }

    public function leaveTable(int $tableId): bool
    {
        Table::where('id', $tableId)
            ->whereIn('status', ['ordering', 'occupied'])
            ->update(['status' => 'free']);

        $table = Table::find($tableId);
        if ($table) {
            broadcastSafe(new TableStatusUpdated($table));
        }

        session()->forget(['table_id', 'table_number', 'cart_count', 'session_started_at']);

        return true;
    }

    public function requestService(int $tableId, string $tableNumber, string $type): void
    {
        broadcastSafe(new ServiceRequestCreated($tableId, $tableNumber, $type));
    }

    public function getActiveOrders(int $tableId, ?string $since = null)
    {
        $query = Order::where('table_id', $tableId)
            ->with('items.product');

        if ($since) {
            $query->where('created_at', '>=', $since);
        }

        return $query->latest()->get();
    }

    public function getSessionOrders(int $tableId, string $sessionStartedAt)
    {
        return Order::where('table_id', $tableId)
            ->where('created_at', '>=', $sessionStartedAt)
            ->with('items.product')
            ->latest()
            ->get();
    }
}
