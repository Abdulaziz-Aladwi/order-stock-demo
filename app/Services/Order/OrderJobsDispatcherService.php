<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Jobs\OnOrderCreated\DecrementProductIngredientStockJob;

class OrderJobsDispatcherService
{
    public function dispatchForStockUpdate(Order $order): void
    {
        foreach($order->products as $product) {
            DecrementProductIngredientStockJob::dispatch($product, $product->pivot->quantity);
        }
    }    
}
