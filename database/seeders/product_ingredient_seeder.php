<?php

namespace Database\Seeders;

use App\Constants\WeightUnit;
use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Seeder;

class product_ingredient_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = Product::first();
        $ingredientsIDs = Ingredient::take(3)->pluck('id');

        $product->ingredients()->attach([
            $ingredientsIDs[0] => ['weight' => 150, 'weight_unit' => WeightUnit::GRAM],
            $ingredientsIDs[1] => ['weight' => 30, 'weight_unit' =>  WeightUnit::GRAM],
            $ingredientsIDs[2] => ['weight' => 20, 'weight_unit' => WeightUnit::GRAM],
        ]);
    }
}
