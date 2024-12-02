<?php

namespace Database\Seeders;

use App\Constants\ProductStatus;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'name' => 'Burger',
            'price' => 100,
            'status' => ProductStatus::ACTIVE,
        ]);
    }
}
