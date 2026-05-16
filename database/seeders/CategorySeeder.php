<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Tenda', 'icon' => '⛺', 'description' => 'Berbagai jenis tenda untuk camping dan hiking'],
            ['name' => 'Sleeping Bag', 'icon' => '🛏️', 'description' => 'Kantong tidur untuk berbagai suhu dan kondisi'],
            ['name' => 'Carrier', 'icon' => '🎒', 'description' => 'Tas carrier dan ransel gunung'],
            ['name' => 'Cooking', 'icon' => '🍳', 'description' => 'Peralatan masak outdoor: kompor, nesting, gas'],
            ['name' => 'Lampu', 'icon' => '🔦', 'description' => 'Lampu headlamp, lentera, dan senter'],
            ['name' => 'Aksesoris', 'icon' => '🧭', 'description' => 'Aksesoris pendukung: matras, trekking pole, dll'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
