<?php

namespace Database\Factories;

use App\Constants\ProductStatus;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'status' => $this->faker->randomElement([ProductStatus::ACTIVE, ProductStatus::INACTIVE]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}