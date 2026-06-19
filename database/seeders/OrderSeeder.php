<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Delivery;
use App\Models\Payment;
use App\Models\Review;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $orders = [
            // === TODAY - Table orders in progress ===
            [
                'order_number' => 'CT_9-6-26-1',
                'table_id' => 1,
                'customer_name' => 'Table T1',
                'type' => 'table',
                'status' => 'preparing',
                'subtotal' => 20.00,
                'delivery_fee' => 0,
                'total' => 20.00,
                'created_at' => $now->copy()->subHours(1),
                'items' => [
                    ['product_id' => 1, 'quantity' => 2, 'unit_price' => 8.50, 'extras' => null, 'total_price' => 17.00],
                    ['product_id' => 18, 'quantity' => 1, 'unit_price' => 3.50, 'extras' => null, 'total_price' => 3.50],
                ],
                'statuses' => [
                    ['status' => 'pending', 'notes' => 'Commande reçue depuis la table', 'created_at' => $now->copy()->subHours(1)],
                    ['status' => 'confirmed', 'notes' => 'Confirmée par le chef', 'created_at' => $now->copy()->subMinutes(55)],
                    ['status' => 'preparing', 'notes' => 'En préparation', 'created_at' => $now->copy()->subMinutes(45)],
                ],
            ],
            [
                'order_number' => 'CT_9-6-26-2',
                'table_id' => 5,
                'customer_name' => 'Table T5',
                'type' => 'table',
                'status' => 'ready',
                'subtotal' => 22.00,
                'delivery_fee' => 0,
                'total' => 22.00,
                'created_at' => $now->copy()->subHours(2),
                'items' => [
                    ['product_id' => 6, 'quantity' => 1, 'unit_price' => 11.00, 'extras' => null, 'total_price' => 11.00],
                    ['product_id' => 12, 'quantity' => 2, 'unit_price' => 2.50, 'extras' => null, 'total_price' => 5.00],
                    ['product_id' => 15, 'quantity' => 1, 'unit_price' => 5.50, 'extras' => null, 'total_price' => 5.50],
                ],
                'statuses' => [
                    ['status' => 'pending', 'notes' => 'Commande reçue depuis la table', 'created_at' => $now->copy()->subHours(2)],
                    ['status' => 'confirmed', 'notes' => 'Confirmée', 'created_at' => $now->copy()->subHours(1)->subMinutes(55)],
                    ['status' => 'preparing', 'notes' => 'En préparation', 'created_at' => $now->copy()->subHours(1)->subMinutes(45)],
                    ['status' => 'ready', 'notes' => 'Prête à servir', 'created_at' => $now->copy()->subMinutes(30)],
                ],
            ],
            [
                'order_number' => 'CT_9-6-26-3',
                'table_id' => 8,
                'customer_name' => 'Table T8',
                'type' => 'table',
                'status' => 'confirmed',
                'subtotal' => 31.00,
                'delivery_fee' => 0,
                'total' => 31.00,
                'created_at' => $now->copy()->subMinutes(30),
                'items' => [
                    ['product_id' => 4, 'quantity' => 1, 'unit_price' => 10.00, 'extras' => null, 'total_price' => 10.00],
                    ['product_id' => 7, 'quantity' => 1, 'unit_price' => 12.50, 'extras' => ['Supplément Poulet'], 'total_price' => 15.50],
                    ['product_id' => 13, 'quantity' => 1, 'unit_price' => 3.50, 'extras' => null, 'total_price' => 3.50],
                ],
                'statuses' => [
                    ['status' => 'pending', 'notes' => 'Commande reçue depuis la table', 'created_at' => $now->copy()->subMinutes(30)],
                    ['status' => 'confirmed', 'notes' => 'Confirmée', 'created_at' => $now->copy()->subMinutes(20)],
                ],
            ],

            // === TODAY - Delivery orders ===
            [
                'order_number' => 'CM_9-6-26-1',
                'user_id' => 2,
                'customer_name' => 'Jean Dupont',
                'customer_phone' => '+221 77 000 00 01',
                'customer_address' => '15 Rue de la République, Dakar',
                'type' => 'delivery',
                'status' => 'preparing',
                'subtotal' => 14.00,
                'delivery_fee' => 0.6,
                'total' => 14.60,
                'created_at' => $now->copy()->subMinutes(45),
                'items' => [
                    ['product_id' => 2, 'quantity' => 1, 'unit_price' => 12.00, 'extras' => ['Double Cheddar'], 'total_price' => 14.00],
                    ['product_id' => 12, 'quantity' => 1, 'unit_price' => 2.50, 'extras' => null, 'total_price' => 2.50],
                ],
                'statuses' => [
                    ['status' => 'pending', 'notes' => 'Commande reçue', 'created_at' => $now->copy()->subMinutes(45)],
                    ['status' => 'confirmed', 'notes' => 'Paiement confirmé', 'created_at' => $now->copy()->subMinutes(42)],
                    ['status' => 'preparing', 'notes' => 'En préparation en cuisine', 'created_at' => $now->copy()->subMinutes(35)],
                ],
                'payment' => ['method' => 'card', 'status' => 'completed', 'amount' => 14.60],
            ],
            [
                'order_number' => 'CM_9-6-26-2',
                'customer_name' => 'Fatou Ndiaye',
                'customer_phone' => '+221 76 123 45 67',
                'customer_address' => '45 Avenue Bourguiba, Dakar',
                'type' => 'delivery',
                'status' => 'pending',
                'subtotal' => 18.00,
                'delivery_fee' => 0.6,
                'total' => 18.60,
                'created_at' => $now->copy()->subMinutes(10),
                'items' => [
                    ['product_id' => 10, 'quantity' => 2, 'unit_price' => 8.00, 'extras' => null, 'total_price' => 16.00],
                    ['product_id' => 19, 'quantity' => 1, 'unit_price' => 4.00, 'extras' => null, 'total_price' => 4.00],
                ],
                'statuses' => [
                    ['status' => 'pending', 'notes' => 'Commande reçue', 'created_at' => $now->copy()->subMinutes(10)],
                ],
            ],

            // === YESTERDAY - Completed orders ===
            [
                'order_number' => 'CT_8-6-26-1',
                'table_id' => 3,
                'customer_name' => 'Table T3',
                'type' => 'table',
                'status' => 'delivered',
                'subtotal' => 27.00,
                'delivery_fee' => 0,
                'total' => 27.00,
                'created_at' => $now->copy()->subDay()->subHours(3),
                'items' => [
                    ['product_id' => 5, 'quantity' => 1, 'unit_price' => 9.00, 'extras' => null, 'total_price' => 9.00],
                    ['product_id' => 6, 'quantity' => 1, 'unit_price' => 11.00, 'extras' => null, 'total_price' => 11.00],
                    ['product_id' => 12, 'quantity' => 2, 'unit_price' => 2.50, 'extras' => null, 'total_price' => 5.00],
                ],
                'statuses' => [
                    ['status' => 'pending', 'notes' => 'Commande reçue depuis la table', 'created_at' => $now->copy()->subDay()->subHours(3)],
                    ['status' => 'confirmed', 'notes' => 'Confirmée', 'created_at' => $now->copy()->subDay()->subHours(2)->subMinutes(55)],
                    ['status' => 'preparing', 'notes' => 'En préparation', 'created_at' => $now->copy()->subDay()->subHours(2)->subMinutes(45)],
                    ['status' => 'ready', 'notes' => 'Prête à servir', 'created_at' => $now->copy()->subDay()->subHours(2)->subMinutes(20)],
                    ['status' => 'delivered', 'notes' => 'Servie à la table', 'created_at' => $now->copy()->subDay()->subHours(2)],
                ],
            ],
            [
                'order_number' => 'CT_8-6-26-2',
                'table_id' => 7,
                'customer_name' => 'Table T7',
                'type' => 'table',
                'status' => 'delivered',
                'subtotal' => 33.00,
                'delivery_fee' => 0,
                'total' => 33.00,
                'created_at' => $now->copy()->subDay()->subHours(5),
                'items' => [
                    ['product_id' => 1, 'quantity' => 1, 'unit_price' => 8.50, 'extras' => ['Supplément Fromage', 'Bacon Extra'], 'total_price' => 12.00],
                    ['product_id' => 8, 'quantity' => 1, 'unit_price' => 13.00, 'extras' => null, 'total_price' => 13.00],
                    ['product_id' => 16, 'quantity' => 1, 'unit_price' => 6.50, 'extras' => null, 'total_price' => 6.50],
                    ['product_id' => 13, 'quantity' => 1, 'unit_price' => 3.50, 'extras' => null, 'total_price' => 3.50],
                ],
                'statuses' => [
                    ['status' => 'pending', 'notes' => 'Commande reçue', 'created_at' => $now->copy()->subDay()->subHours(5)],
                    ['status' => 'confirmed', 'notes' => 'Confirmée', 'created_at' => $now->copy()->subDay()->subHours(4)->subMinutes(55)],
                    ['status' => 'preparing', 'notes' => 'En préparation', 'created_at' => $now->copy()->subDay()->subHours(4)->subMinutes(45)],
                    ['status' => 'ready', 'notes' => 'Prête', 'created_at' => $now->copy()->subDay()->subHours(4)->subMinutes(20)],
                    ['status' => 'delivered', 'notes' => 'Servie à la table T7', 'created_at' => $now->copy()->subDay()->subHours(4)],
                ],
            ],
            [
                'order_number' => 'CM_8-6-26-1',
                'user_id' => 2,
                'customer_name' => 'Jean Dupont',
                'customer_phone' => '+221 77 000 00 01',
                'customer_address' => '15 Rue de la République, Dakar',
                'type' => 'delivery',
                'status' => 'delivered',
                'subtotal' => 24.50,
                'delivery_fee' => 0.6,
                'total' => 25.10,
                'created_at' => $now->copy()->subDay()->subHours(6),
                'items' => [
                    ['product_id' => 3, 'quantity' => 1, 'unit_price' => 9.50, 'extras' => null, 'total_price' => 9.50],
                    ['product_id' => 5, 'quantity' => 1, 'unit_price' => 9.00, 'extras' => null, 'total_price' => 9.00],
                    ['product_id' => 18, 'quantity' => 2, 'unit_price' => 3.50, 'extras' => null, 'total_price' => 7.00],
                ],
                'statuses' => [
                    ['status' => 'pending', 'notes' => 'Commande reçue', 'created_at' => $now->copy()->subDay()->subHours(6)],
                    ['status' => 'confirmed', 'notes' => 'Paiement confirmé', 'created_at' => $now->copy()->subDay()->subHours(5)->subMinutes(57)],
                    ['status' => 'preparing', 'notes' => 'En préparation', 'created_at' => $now->copy()->subDay()->subHours(5)->subMinutes(50)],
                    ['status' => 'ready', 'notes' => 'Prête pour livraison', 'created_at' => $now->copy()->subDay()->subHours(5)->subMinutes(30)],
                    ['status' => 'delivered', 'notes' => 'Livrée au client', 'created_at' => $now->copy()->subDay()->subHours(5)->subMinutes(10)],
                ],
                'delivery' => ['delivery_person_name' => 'Amadou Diallo', 'status' => 'delivered', 'estimated_time' => '30 min', 'delivered_at' => $now->copy()->subDay()->subHours(5)->subMinutes(10)],
                'payment' => ['method' => 'card', 'status' => 'completed', 'amount' => 25.10],
            ],
            [
                'order_number' => 'CM_8-6-26-2',
                'customer_name' => 'Aminata Sow',
                'customer_phone' => '+221 78 987 65 43',
                'customer_address' => '8 Rue Félix Faure, Dakar',
                'type' => 'delivery',
                'status' => 'delivered',
                'subtotal' => 15.00,
                'delivery_fee' => 0.6,
                'total' => 15.60,
                'created_at' => $now->copy()->subDay()->subHours(8),
                'items' => [
                    ['product_id' => 9, 'quantity' => 2, 'unit_price' => 7.50, 'extras' => null, 'total_price' => 15.00],
                    ['product_id' => 14, 'quantity' => 1, 'unit_price' => 1.50, 'extras' => null, 'total_price' => 1.50],
                ],
                'statuses' => [
                    ['status' => 'pending', 'notes' => 'Commande reçue', 'created_at' => $now->copy()->subDay()->subHours(8)],
                    ['status' => 'confirmed', 'notes' => 'Paiement confirmé', 'created_at' => $now->copy()->subDay()->subHours(7)->subMinutes(57)],
                    ['status' => 'preparing', 'notes' => 'En préparation', 'created_at' => $now->copy()->subDay()->subHours(7)->subMinutes(50)],
                    ['status' => 'ready', 'notes' => 'Prête pour livraison', 'created_at' => $now->copy()->subDay()->subHours(7)->subMinutes(30)],
                    ['status' => 'delivered', 'notes' => 'Livrée au client', 'created_at' => $now->copy()->subDay()->subHours(7)->subMinutes(5)],
                ],
                'delivery' => ['delivery_person_name' => 'Moussa Faye', 'status' => 'delivered', 'estimated_time' => '25 min', 'delivered_at' => $now->copy()->subDay()->subHours(7)->subMinutes(5)],
                'payment' => ['method' => 'cash', 'status' => 'completed', 'amount' => 15.60],
            ],

            // === 2 DAYS AGO - Cancelled order ===
            [
                'order_number' => 'CT_7-6-26-1',
                'table_id' => 10,
                'customer_name' => 'Table T10',
                'type' => 'table',
                'status' => 'cancelled',
                'subtotal' => 16.00,
                'delivery_fee' => 0,
                'total' => 16.00,
                'created_at' => $now->copy()->subDays(2)->subHours(4),
                'items' => [
                    ['product_id' => 11, 'quantity' => 1, 'unit_price' => 11.00, 'extras' => null, 'total_price' => 11.00],
                    ['product_id' => 20, 'quantity' => 1, 'unit_price' => 2.50, 'extras' => null, 'total_price' => 2.50],
                ],
                'statuses' => [
                    ['status' => 'pending', 'notes' => 'Commande reçue', 'created_at' => $now->copy()->subDays(2)->subHours(4)],
                    ['status' => 'cancelled', 'notes' => 'Annulée par le client', 'created_at' => $now->copy()->subDays(2)->subHours(3)->subMinutes(45)],
                ],
            ],
            [
                'order_number' => 'CM_7-6-26-1',
                'customer_name' => 'Omar Ba',
                'customer_phone' => '+221 70 555 55 55',
                'customer_address' => '12 Rue de la Paix, Dakar',
                'type' => 'delivery',
                'status' => 'cancelled',
                'subtotal' => 15.50,
                'delivery_fee' => 0.6,
                'total' => 16.10,
                'created_at' => $now->copy()->subDays(2)->subHours(6),
                'items' => [
                    ['product_id' => 3, 'quantity' => 1, 'unit_price' => 9.50, 'extras' => null, 'total_price' => 9.50],
                    ['product_id' => 18, 'quantity' => 1, 'unit_price' => 3.50, 'extras' => null, 'total_price' => 3.50],
                ],
                'statuses' => [
                    ['status' => 'pending', 'notes' => 'Commande reçue', 'created_at' => $now->copy()->subDays(2)->subHours(6)],
                    ['status' => 'cancelled', 'notes' => 'Annulée par le client', 'created_at' => $now->copy()->subDays(2)->subHours(5)->subMinutes(30)],
                ],
                'payment' => ['method' => 'cash', 'status' => 'refunded', 'amount' => 16.10],
            ],

            // === 3 DAYS AGO - More history ===
            [
                'order_number' => 'CM_6-6-26-1',
                'user_id' => 2,
                'customer_name' => 'Jean Dupont',
                'customer_phone' => '+221 77 000 00 01',
                'customer_address' => '15 Rue de la République, Dakar',
                'type' => 'delivery',
                'status' => 'delivered',
                'subtotal' => 35.50,
                'delivery_fee' => 0.6,
                'total' => 36.10,
                'created_at' => $now->copy()->subDays(3)->subHours(2),
                'items' => [
                    ['product_id' => 4, 'quantity' => 1, 'unit_price' => 10.00, 'extras' => null, 'total_price' => 10.00],
                    ['product_id' => 6, 'quantity' => 1, 'unit_price' => 11.00, 'extras' => null, 'total_price' => 11.00],
                    ['product_id' => 13, 'quantity' => 2, 'unit_price' => 3.50, 'extras' => null, 'total_price' => 7.00],
                    ['product_id' => 17, 'quantity' => 1, 'unit_price' => 5.00, 'extras' => null, 'total_price' => 5.00],
                ],
                'statuses' => [
                    ['status' => 'pending', 'notes' => 'Commande reçue', 'created_at' => $now->copy()->subDays(3)->subHours(2)],
                    ['status' => 'confirmed', 'notes' => 'Paiement confirmé', 'created_at' => $now->copy()->subDays(3)->subHours(1)->subMinutes(57)],
                    ['status' => 'preparing', 'notes' => 'En préparation', 'created_at' => $now->copy()->subDays(3)->subHours(1)->subMinutes(50)],
                    ['status' => 'ready', 'notes' => 'Prête pour livraison', 'created_at' => $now->copy()->subDays(3)->subHours(1)->subMinutes(25)],
                    ['status' => 'delivered', 'notes' => 'Livrée', 'created_at' => $now->copy()->subDays(3)->subHours(1)],
                ],
                'delivery' => ['delivery_person_name' => 'Amadou Diallo', 'status' => 'delivered', 'estimated_time' => '25 min', 'delivered_at' => $now->copy()->subDays(3)->subHours(1)],
                'payment' => ['method' => 'card', 'status' => 'completed', 'amount' => 36.10],
            ],
            [
                'order_number' => 'CT_6-6-26-1',
                'table_id' => 12,
                'customer_name' => 'Table T12',
                'type' => 'table',
                'status' => 'delivered',
                'subtotal' => 16.50,
                'delivery_fee' => 0,
                'total' => 16.50,
                'created_at' => $now->copy()->subDays(3)->subHours(5),
                'items' => [
                    ['product_id' => 9, 'quantity' => 1, 'unit_price' => 7.50, 'extras' => null, 'total_price' => 7.50],
                    ['product_id' => 10, 'quantity' => 1, 'unit_price' => 8.00, 'extras' => null, 'total_price' => 8.00],
                    ['product_id' => 14, 'quantity' => 1, 'unit_price' => 1.50, 'extras' => null, 'total_price' => 1.50],
                ],
                'statuses' => [
                    ['status' => 'pending', 'notes' => 'Commande reçue', 'created_at' => $now->copy()->subDays(3)->subHours(5)],
                    ['status' => 'confirmed', 'notes' => 'Confirmée', 'created_at' => $now->copy()->subDays(3)->subHours(4)->subMinutes(55)],
                    ['status' => 'preparing', 'notes' => 'En préparation', 'created_at' => $now->copy()->subDays(3)->subHours(4)->subMinutes(45)],
                    ['status' => 'ready', 'notes' => 'Prête', 'created_at' => $now->copy()->subDays(3)->subHours(4)->subMinutes(20)],
                    ['status' => 'delivered', 'notes' => 'Servie', 'created_at' => $now->copy()->subDays(3)->subHours(4)],
                ],
            ],
        ];

        foreach ($orders as $data) {
            $items = $data['items'];
            $statuses = $data['statuses'];
            $deliveryData = $data['delivery'] ?? null;
            $paymentData = $data['payment'] ?? null;
            $createdAt = $data['created_at'];
            unset($data['items'], $data['statuses'], $data['delivery'], $data['payment'], $data['created_at']);

            $data['created_at'] = $createdAt;
            $data['updated_at'] = $createdAt;

            $order = Order::create($data);

            foreach ($items as $item) {
                $item['order_id'] = $order->id;
                $item['extras'] = $item['extras'] ?? null;
                $item['created_at'] = $createdAt;
                $item['updated_at'] = $createdAt;
                OrderItem::create($item);
            }

            foreach ($statuses as $i => $status) {
                $status['order_id'] = $order->id;
                $status['updated_at'] = $status['created_at'];
                OrderStatus::create($status);
            }

            if ($deliveryData) {
                $deliveryData['order_id'] = $order->id;
                $deliveryData['created_at'] = $createdAt;
                $deliveryData['updated_at'] = $createdAt;
                Delivery::create($deliveryData);
            }

            if ($paymentData) {
                $paymentData['order_id'] = $order->id;
                $paymentData['transaction_id'] = 'TXN-' . strtoupper(substr(md5(rand()), 0, 12));
                $paymentData['created_at'] = $createdAt;
                $paymentData['updated_at'] = $createdAt;
                Payment::create($paymentData);
            }
        }

        // Reviews
        $reviews = [
            ['product_id' => 1, 'order_id' => 1, 'author_name' => 'Lucas M.', 'rating' => 5, 'comment' => 'Excellent burger, la viande est parfaitement cuite !'],
            ['product_id' => 2, 'order_id' => 5, 'author_name' => 'Sarah K.', 'rating' => 4, 'comment' => 'Le double cheese est incroyable, très généreux.'],
            ['product_id' => 5, 'order_id' => 6, 'author_name' => 'Thomas R.', 'rating' => 5, 'comment' => 'Meilleure pizza margherita de Dakar !'],
            ['product_id' => 10, 'order_id' => 5, 'author_name' => 'Aminata D.', 'rating' => 4, 'comment' => 'Tacos poulet délicieux, bien épicé.'],
            ['product_id' => 15, 'order_id' => 2, 'author_name' => 'Omar S.', 'rating' => 5, 'comment' => 'Le tiramisu est un pur délice, fait maison comme j\'aime.'],
            ['product_id' => 4, 'order_id' => 11, 'author_name' => 'Mariam B.', 'rating' => 4, 'comment' => 'Burger végétarien super bon, même les carnivores apprécient !'],
            ['product_id' => 8, 'order_id' => 7, 'author_name' => 'Patrick N.', 'rating' => 5, 'comment' => 'Pizza 4 fromages, un régal ! Le miel fait toute la différence.'],
            ['product_id' => 11, 'order_id' => 10, 'author_name' => 'Khady F.', 'rating' => 5, 'comment' => 'Taco Supreme de folie, bien relevé et copieux.'],
            ['product_id' => 18, 'order_id' => 8, 'author_name' => 'Moussa D.', 'rating' => 4, 'comment' => 'Frites maison croustillantes comme on les aime.'],
            ['product_id' => 16, 'order_id' => 7, 'author_name' => 'Aïcha S.', 'rating' => 5, 'comment' => 'Fondant au chocolat divin, la glace vanille est parfaite.'],
        ];

        foreach ($reviews as $review) {
            Review::create($review);
        }
    }
}
