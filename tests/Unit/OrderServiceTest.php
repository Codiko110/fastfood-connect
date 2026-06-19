<?php

namespace Tests\Unit;

use App\Services\OrderService;
use PHPUnit\Framework\TestCase;

class OrderServiceTest extends TestCase
{
    private OrderService $orderService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderService = new OrderService();
    }

    public function test_get_status_label_returns_correct_labels(): void
    {
        $this->assertEquals('Nouvelle', $this->orderService->getStatusLabel('pending'));
        $this->assertEquals('Confirmée', $this->orderService->getStatusLabel('confirmed'));
        $this->assertEquals('En préparation', $this->orderService->getStatusLabel('preparing'));
        $this->assertEquals('Prête', $this->orderService->getStatusLabel('ready'));
        $this->assertEquals('Livrée', $this->orderService->getStatusLabel('delivered'));
        $this->assertEquals('Annulée', $this->orderService->getStatusLabel('cancelled'));
    }

    public function test_get_delivery_status_label_returns_correct_labels(): void
    {
        $this->assertEquals('En attente', $this->orderService->getDeliveryStatusLabel('pending'));
        $this->assertEquals('Assignée', $this->orderService->getDeliveryStatusLabel('assigned'));
        $this->assertEquals('En cours', $this->orderService->getDeliveryStatusLabel('in_transit'));
        $this->assertEquals('Livrée', $this->orderService->getDeliveryStatusLabel('delivered'));
        $this->assertEquals('Échouée', $this->orderService->getDeliveryStatusLabel('failed'));
    }

    public function test_get_timeline_label_returns_correct_labels(): void
    {
        $this->assertEquals('Commande envoyée', $this->orderService->getTimelineLabel('pending'));
        $this->assertEquals('Prête à servir', $this->orderService->getTimelineLabel('ready'));
        $this->assertEquals('Annulée', $this->orderService->getTimelineLabel('cancelled'));
    }

    public function test_get_order_status_priority_returns_correct_order(): void
    {
        $this->assertEquals(1, $this->orderService->getOrderStatusPriority('pending'));
        $this->assertEquals(2, $this->orderService->getOrderStatusPriority('confirmed'));
        $this->assertEquals(3, $this->orderService->getOrderStatusPriority('preparing'));
        $this->assertEquals(4, $this->orderService->getOrderStatusPriority('ready'));
        $this->assertEquals(5, $this->orderService->getOrderStatusPriority('delivered'));
        $this->assertEquals(6, $this->orderService->getOrderStatusPriority('cancelled'));
    }

    public function test_get_status_label_falls_back_to_status(): void
    {
        $this->assertEquals('unknown', $this->orderService->getStatusLabel('unknown'));
    }
}
