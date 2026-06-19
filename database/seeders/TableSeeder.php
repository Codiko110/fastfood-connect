<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 12; $i++) {
            Table::create([
                'table_number' => "T$i",
                'capacity' => $i <= 4 ? 2 : ($i <= 8 ? 4 : 6),
                'status' => 'free',
            ]);
        }
    }
}
