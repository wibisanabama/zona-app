<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('name');

        $items = [
            ['category' => 'Tenda', 'sku' => 'TND-001', 'name' => 'Tenda Great Outdoor Java 4P', 'daily_rate' => 75000, 'deposit_amount' => 150000, 'stock_total' => 5, 'description' => 'Tenda kapasitas 4 orang, double layer, waterproof'],
            ['category' => 'Tenda', 'sku' => 'TND-002', 'name' => 'Tenda Consina Magnum 6P', 'daily_rate' => 100000, 'deposit_amount' => 200000, 'stock_total' => 3, 'description' => 'Tenda besar kapasitas 6 orang dengan vestibule'],
            ['category' => 'Tenda', 'sku' => 'TND-003', 'name' => 'Tenda Eiger Falcon 2P', 'daily_rate' => 60000, 'deposit_amount' => 120000, 'stock_total' => 4, 'description' => 'Tenda ultralight untuk 2 orang'],
            ['category' => 'Sleeping Bag', 'sku' => 'SB-001', 'name' => 'Sleeping Bag Polar Bulu', 'daily_rate' => 25000, 'deposit_amount' => 50000, 'stock_total' => 10, 'description' => 'Sleeping bag bulu tebal, comfort temp 5°C'],
            ['category' => 'Sleeping Bag', 'sku' => 'SB-002', 'name' => 'Sleeping Bag Dacron', 'daily_rate' => 20000, 'deposit_amount' => 40000, 'stock_total' => 8, 'description' => 'Sleeping bag dacron standar camping'],
            ['category' => 'Carrier', 'sku' => 'CR-001', 'name' => 'Carrier Deuter 60L', 'daily_rate' => 50000, 'deposit_amount' => 100000, 'stock_total' => 6, 'description' => 'Carrier premium 60 liter dengan rain cover'],
            ['category' => 'Carrier', 'sku' => 'CR-002', 'name' => 'Carrier Eiger 45L', 'daily_rate' => 40000, 'deposit_amount' => 80000, 'stock_total' => 5, 'description' => 'Carrier mid-size 45 liter cocok untuk weekend'],
            ['category' => 'Carrier', 'sku' => 'CR-003', 'name' => 'Daypack Consina 30L', 'daily_rate' => 25000, 'deposit_amount' => 50000, 'stock_total' => 7, 'description' => 'Daypack ringan untuk day hike'],
            ['category' => 'Cooking', 'sku' => 'CK-001', 'name' => 'Kompor Windproof + Gas', 'daily_rate' => 20000, 'deposit_amount' => 50000, 'stock_total' => 6, 'description' => 'Kompor portable anti angin + tabung gas 230g'],
            ['category' => 'Cooking', 'sku' => 'CK-002', 'name' => 'Nesting Set 3-4P', 'daily_rate' => 15000, 'deposit_amount' => 30000, 'stock_total' => 8, 'description' => 'Set panci dan wajan camping untuk 3-4 orang'],
            ['category' => 'Cooking', 'sku' => 'CK-003', 'name' => 'Teko Outdoor 1.5L', 'daily_rate' => 10000, 'deposit_amount' => 25000, 'stock_total' => 5, 'description' => 'Teko aluminium untuk masak air di gunung'],
            ['category' => 'Lampu', 'sku' => 'LP-001', 'name' => 'Headlamp LED 300lm', 'daily_rate' => 10000, 'deposit_amount' => 30000, 'stock_total' => 10, 'description' => 'Headlamp rechargeable 300 lumen'],
            ['category' => 'Lampu', 'sku' => 'LP-002', 'name' => 'Lentera Camping LED', 'daily_rate' => 15000, 'deposit_amount' => 35000, 'stock_total' => 6, 'description' => 'Lentera LED gantung untuk tenda'],
            ['category' => 'Aksesoris', 'sku' => 'AK-001', 'name' => 'Matras Foam 5mm', 'daily_rate' => 10000, 'deposit_amount' => 20000, 'stock_total' => 12, 'description' => 'Matras tidur foam standar'],
            ['category' => 'Aksesoris', 'sku' => 'AK-002', 'name' => 'Trekking Pole Pair', 'daily_rate' => 15000, 'deposit_amount' => 40000, 'stock_total' => 5, 'description' => 'Sepasang trekking pole aluminium adjustable'],
            ['category' => 'Aksesoris', 'sku' => 'AK-003', 'name' => 'Raincoat Poncho', 'daily_rate' => 10000, 'deposit_amount' => 20000, 'stock_total' => 8, 'description' => 'Jas hujan poncho untuk hiking'],
        ];

        foreach ($items as $itemData) {
            $cat = $categories->get($itemData['category']);
            if ($cat) {
                Item::create([
                    'category_id' => $cat->id,
                    'sku' => $itemData['sku'],
                    'name' => $itemData['name'],
                    'description' => $itemData['description'],
                    'daily_rate' => $itemData['daily_rate'],
                    'deposit_amount' => $itemData['deposit_amount'],
                    'stock_total' => $itemData['stock_total'],
                    'stock_available' => $itemData['stock_total'],
                    'condition' => 'baik',
                ]);
            }
        }
    }
}
