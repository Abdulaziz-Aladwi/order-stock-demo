<?php

namespace Database\Factories;

use App\Constants\WeightUnit;
use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;

class IngredientFactory extends Factory
{
    protected $model = Ingredient::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'total_weight' => $this->faker->randomDigit(),
            'remaining_weight' => $this->faker->randomDigit(), 
            'weight_unit' => $this->faker->randomElement([WeightUnit::GRAM, WeightUnit::KILOGRAM]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
