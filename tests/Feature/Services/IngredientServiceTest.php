<?php

namespace Tests\Feature\Services;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Ingredient;
use App\Constants\WeightUnit;
use App\Constants\ProductStatus;
use App\Services\Ingredient\IngredientService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IngredientServiceTest extends TestCase
{
    use RefreshDatabase;

    private IngredientService $ingredientService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ingredientService = new IngredientService();
    }
    
    public function test_disable_products_when_ingredient_stock_is_not_available()
    {
        $ingredient = Ingredient::factory()->create(['remaining_weight' => 0]);
        $products = Product::factory()->count(3)->create(['status' => ProductStatus::ACTIVE]);
        $ingredient->products()->attach($products, ['weight' => 150, 'weight_unit' => WeightUnit::GRAM]);

        $this->ingredientService->disableProductsIfIngredientStockNotAvailable($ingredient);

        $products->each(function ($product) {
            $this->assertEquals(ProductStatus::INACTIVE, $product->fresh()->status);
        });
    }
}
