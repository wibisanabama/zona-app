<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'Andi Prasetyo', 'phone' => '081234567001', 'identity_type' => 'ktp', 'identity_number' => '3204012345678901', 'address' => 'Jl. Raya Cianjur No. 10, Cianjur', 'email' => 'andi@email.com'],
            ['name' => 'Siti Nurhaliza', 'phone' => '081234567002', 'identity_type' => 'ktp', 'identity_number' => '3204012345678902', 'address' => 'Jl. Siliwangi No. 25, Cipanas', 'email' => 'siti@email.com'],
            ['name' => 'Budi Santoso', 'phone' => '081234567003', 'identity_type' => 'sim', 'identity_number' => '1234567890123', 'address' => 'Jl. Merdeka No. 5, Bandung'],
            ['name' => 'Dewi Lestari', 'phone' => '081234567004', 'identity_type' => 'ktp', 'identity_number' => '3204012345678904', 'address' => 'Jl. Gunung Padang No. 8, Cianjur'],
            ['name' => 'Rizky Firmansyah', 'phone' => '081234567005', 'identity_type' => 'ktp', 'identity_number' => '3204012345678905', 'address' => 'Jl. Raya Pacet No. 15, Pacet', 'email' => 'rizky@email.com'],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
