<?php

namespace Tests\Feature\Jobs\OnOrderCreated;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Ingredient;
use App\Services\Ingredient\IngredientService;
use App\Jobs\OnOrderCreated\DecrementProductIngredientStockJob;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DecrementProductIngredientStockJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_decrements_ingredient_stocks_correctly()
    {
        $ingredients = [
            Ingredient::factory()->create([
                'name' => 'Beef',
                'remaining_weight' => 10.0,
                'total_weight' => 10.0,
            ])
        ];

        $product = Product::factory()->create(['name' => 'Burger']);        
        $product->ingredients()->attach([
            $ingredients[0]->id => ['weight' => 250],
        ]);

        $quantity = 2;
        $job = new DecrementProductIngredientStockJob($product, $quantity);
        $job->handle(app(IngredientService::class));

        $this->assertEquals(9.5, $ingredients[0]->fresh()->remaining_weight);
    }
}
