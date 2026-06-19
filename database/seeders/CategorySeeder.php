<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Burgers', 'slug' => 'burgers', 'description' => 'Nos délicieux burgers artisanaux', 'sort_order' => 1],
            ['name' => 'Pizzas', 'slug' => 'pizzas', 'description' => 'Pizzas cuites au feu de bois', 'sort_order' => 2],
            ['name' => 'Tacos', 'slug' => 'tacos', 'description' => 'Tacos savoureux et épicés', 'sort_order' => 3],
            ['name' => 'Boissons', 'slug' => 'boissons', 'description' => 'Boissons fraîches et désaltérantes', 'sort_order' => 4],
            ['name' => 'Desserts', 'slug' => 'desserts', 'description' => 'Desserts gourmands', 'sort_order' => 5],
            ['name' => 'Accompagnements', 'slug' => 'accompagnements', 'description' => 'Frites, onions rings et plus', 'sort_order' => 6],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
