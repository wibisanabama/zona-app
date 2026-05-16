<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin account
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@zonaadventure.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
            'phone' => '081234567890',
        ]);

        // Kasir account
        User::factory()->create([
            'name' => 'Kasir Zona',
            'email' => 'kasir@zonaadventure.id',
            'password' => bcrypt('password'),
            'role' => 'kasir',
            'is_active' => true,
            'phone' => '081234567891',
        ]);

        // Seed categories
        $this->call(CategorySeeder::class);

        // Seed items
        $this->call(ItemSeeder::class);

        // Seed customers
        $this->call(CustomerSeeder::class);
    }
}
