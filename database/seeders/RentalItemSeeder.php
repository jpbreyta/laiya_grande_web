<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RentalItem;

class RentalItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Portable Gas Stove', 'category' => 'Kitchen', 'price' => 250.00, 'stock_quantity' => 10],
            ['name' => 'Extra Plates (Set of 6)', 'category' => 'Kitchen', 'price' => 50.00, 'stock_quantity' => 50],
            ['name' => 'Karaoke Machine', 'category' => 'Entertainment', 'price' => 1000.00, 'stock_quantity' => 3],
            ['name' => 'Rice Cooker', 'category' => 'Kitchen', 'price' => 150.00, 'stock_quantity' => 5],
            ['name' => 'Grill Set', 'category' => 'Kitchen', 'price' => 200.00, 'stock_quantity' => 8],
            ['name' => 'Towel Rental', 'category' => 'Toiletries', 'price' => 100.00, 'stock_quantity' => 100],
        ];

        foreach ($items as $item) {
            RentalItem::create($item);
        }
    }
}