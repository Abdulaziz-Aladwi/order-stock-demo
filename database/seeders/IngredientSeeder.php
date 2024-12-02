<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Ingredient;
use App\Constants\WeightUnit;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        $ingredients = array(
            [
                'name' => 'Beef',
                'total_weight' => 20,
                'remaining_weight' => 20,
                'weight_unit' => WeightUnit::GRAM,
                'email_notification_sent' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Cheese',
                'total_weight' => 5,
                'remaining_weight' => 5,
                'weight_unit' => WeightUnit::GRAM,
                'email_notification_sent' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Onion',
                'total_weight' => 1,
                'remaining_weight' => 1,
                'weight_unit' => WeightUnit::GRAM,
                'email_notification_sent' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        );

        Ingredient::insert($ingredients);
    }
}
