<?php

namespace App\Services;

use App\Models\Delivery;
use App\Events\DeliveryStatusUpdated;

class DeliveryService
{
    public function getDeliveriesWithFilters(?string $search = null)
    {
        $query = Delivery::with('order')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('delivery_person_name', 'ilike', '%' . $search . '%')
                  ->orWhereHas('order', function ($oq) use ($search) {
                      $oq->where('order_number', 'ilike', '%' . $search . '%');
                  });
            });
        }

        return $query->paginate(10);
    }

    public function assignDelivery(Delivery $delivery, string $personName): void
    {
        $delivery->update([
            'delivery_person_name' => $personName,
            'status' => 'assigned',
        ]);

        broadcastSafe(new DeliveryStatusUpdated($delivery));
    }

    public function updateStatus(Delivery $delivery, string $status): void
    {
        $data = ['status' => $status];

        if ($status === 'delivered') {
            $data['delivered_at'] = now();
        }

        $delivery->update($data);
        broadcastSafe(new DeliveryStatusUpdated($delivery));
    }

    public function getDeliveryCounts(): array
    {
        return [
            'pendingDeliveries' => Delivery::where('status', 'pending')->count(),
            'activeDeliveries' => Delivery::whereIn('status', ['assigned', 'in_transit'])->count(),
        ];
    }
}
