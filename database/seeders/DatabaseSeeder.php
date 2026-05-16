<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

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
    }
}
