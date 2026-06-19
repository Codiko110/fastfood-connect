<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin FlashFood',
            'email' => 'admin@fastfood.com',
            'password' => 'password',
            'phone' => '+221 77 000 00 00',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Jean Dupont',
            'email' => 'jean@client.com',
            'password' => 'password',
            'phone' => '+221 77 000 00 01',
            'role' => 'client',
        ]);
    }
}
