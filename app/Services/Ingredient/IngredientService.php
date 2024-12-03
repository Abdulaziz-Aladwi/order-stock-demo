<?php

namespace App\Services\Ingredient;

use App\Models\Ingredient;
use App\Constants\ProductStatus;

class IngredientService
{
    public function disableProductsIfIngredientStockNotAvailable(Ingredient $ingredient): void
    {
        if (!$ingredient->isStockAvailable) {
            $ingredient->products()->update(['status' => ProductStatus::INACTIVE]);
        }
    }
}
