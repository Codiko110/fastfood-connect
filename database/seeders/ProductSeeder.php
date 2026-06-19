<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductExtra;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['category_id' => 1, 'name' => 'Classic Burger', 'description' => 'Steak haché 150g, salade, tomate, oignon, sauce maison', 'price' => 8.50, 'preparation_time' => 12, 'rating' => 4.5, 'image' => 'products/Burger-Train (934).jpeg'],
            ['category_id' => 1, 'name' => 'Double Cheese', 'description' => 'Double steak, cheddar fondu, bacon croustillant, sauce BBQ', 'price' => 12.00, 'preparation_time' => 15, 'rating' => 4.8, 'image' => 'products/Burger-Train (950).jpeg'],
            ['category_id' => 1, 'name' => 'Chicken Deluxe', 'description' => 'Poulet pané, salade, tomate, mayo, pain brioché', 'price' => 9.50, 'preparation_time' => 10, 'rating' => 4.2, 'image' => 'products/Crispy Chicken-Train (910).jpeg'],
            ['category_id' => 1, 'name' => 'Veggie Burger', 'description' => 'Steak végétal, avocat, salade, tomates séchées, sauce verte', 'price' => 10.00, 'preparation_time' => 10, 'rating' => 4.0, 'image' => 'products/Burger-Train (977).jpeg'],
            ['category_id' => 2, 'name' => 'Margherita', 'description' => 'Sauce tomate, mozzarella, basilic frais', 'price' => 9.00, 'preparation_time' => 18, 'rating' => 4.6, 'image' => 'products/Pizza-Train (47).jpeg'],
            ['category_id' => 2, 'name' => 'Pepperoni', 'description' => 'Sauce tomate, mozzarella, pepperoni, origan', 'price' => 11.00, 'preparation_time' => 20, 'rating' => 4.7, 'image' => 'products/Pizza-Train (68).jpeg'],
            ['category_id' => 2, 'name' => 'Royale', 'description' => 'Crème fraîche, poulet, champignons, emmental', 'price' => 12.50, 'preparation_time' => 22, 'rating' => 4.5, 'image' => 'products/Pizza-Train (71).jpeg'],
            ['category_id' => 2, 'name' => '4 Fromages', 'description' => 'Mozzarella, gorgonzola, chèvre, emmental, miel', 'price' => 13.00, 'original_price' => 15.00, 'preparation_time' => 20, 'rating' => 4.8, 'image' => 'products/Pizza-Train (702).jpeg'],
            ['category_id' => 3, 'name' => 'Taco Boeuf', 'description' => 'Boeuf haché, salade, fromage, sauce blanche', 'price' => 7.50, 'preparation_time' => 8, 'rating' => 4.3, 'image' => 'products/Taco-Train (795).jpeg'],
            ['category_id' => 3, 'name' => 'Taco Poulet', 'description' => 'Poulet mariné, guacamole, salsa, crème', 'price' => 8.00, 'preparation_time' => 10, 'rating' => 4.4, 'image' => 'products/Taco-Train (821).jpeg'],
            ['category_id' => 3, 'name' => 'Taco Supreme', 'description' => 'Boeuf, poulet, chorizo, 3 fromages, sauce signature', 'price' => 11.00, 'preparation_time' => 12, 'rating' => 4.9, 'image' => 'products/Taquito-Train (1050).jpeg'],
            ['category_id' => 4, 'name' => 'Coca Cola', 'description' => 'Canette 33cl', 'price' => 2.50, 'preparation_time' => 1, 'rating' => 4.0, 'image' => 'products/Hot Dog - Train (824).jpeg'],
            ['category_id' => 4, 'name' => "Jus d'Orange", 'description' => 'Jus frais pressé 25cl', 'price' => 3.50, 'preparation_time' => 3, 'rating' => 4.5, 'image' => 'products/Sandwich-Train (737).jpeg'],
            ['category_id' => 4, 'name' => 'Eau Minérale', 'description' => 'Bouteille 50cl', 'price' => 1.50, 'preparation_time' => 1, 'rating' => 4.0, 'image' => 'products/Baked Potato-Train (791).jpeg'],
            ['category_id' => 5, 'name' => 'Tiramisu', 'description' => 'Tiramisu maison au café', 'price' => 5.50, 'preparation_time' => 2, 'rating' => 4.6, 'image' => 'products/Donut (934).jpeg'],
            ['category_id' => 5, 'name' => 'Fondant Chocolat', 'description' => 'Fondant au chocolat noir, glace vanille', 'price' => 6.50, 'preparation_time' => 8, 'rating' => 4.8, 'image' => 'products/Donut (936).jpeg'],
            ['category_id' => 5, 'name' => 'Moelleux Citron', 'description' => 'Moelleux au citron, zestes confits', 'price' => 5.00, 'preparation_time' => 6, 'rating' => 4.3, 'image' => 'products/Donut (950).jpeg'],
            ['category_id' => 6, 'name' => 'Frites Maison', 'description' => 'Frites fraîches croustillantes', 'price' => 3.50, 'preparation_time' => 8, 'rating' => 4.5, 'image' => 'products/Fries-Train (1390).jpeg'],
            ['category_id' => 6, 'name' => 'Onion Rings', 'description' => "Beignets d'oignons panés", 'price' => 4.00, 'preparation_time' => 7, 'rating' => 4.2, 'image' => 'products/Fries-Train (1399).jpeg'],
            ['category_id' => 6, 'name' => 'Coleslaw', 'description' => 'Salade de chou crémeuse', 'price' => 2.50, 'preparation_time' => 2, 'rating' => 3.8, 'image' => 'products/Baked Potato-Train (827).jpeg'],
        ];

        foreach ($products as $data) {
            Product::create($data);
        }

        $extras = [
            ['product_id' => 1, 'name' => 'Supplément Fromage', 'price' => 1.50],
            ['product_id' => 1, 'name' => 'Bacon Extra', 'price' => 2.00],
            ['product_id' => 1, 'name' => 'Oignons Caramélisés', 'price' => 1.00],
            ['product_id' => 2, 'name' => 'Double Cheddar', 'price' => 2.00],
            ['product_id' => 2, 'name' => 'Sauce BBQ', 'price' => 0.50],
            ['product_id' => 7, 'name' => 'Supplément Poulet', 'price' => 3.00],
            ['product_id' => 7, 'name' => 'Champignons Extra', 'price' => 1.50],
        ];

        foreach ($extras as $data) {
            ProductExtra::create($data);
        }
    }
}
