<?php

namespace App\Jobs\OnOrderCreated;

use App\Models\Product;
use App\Models\Ingredient;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Ingredient\IngredientService;
use App\Jobs\OnStockUpdated\SendLowStockEmailNotificationJob;
use Illuminate\Support\Facades\Log;

class DecrementProductIngredientStockJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Product $product;
    protected int $quantity;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Product $product, int $quantity)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(IngredientService $ingredientService)
    {
        try {
            $product = $this->product;
            foreach($product->ingredients as $ingredient) {
                $lock = Cache::lock("ingredient{$ingredient->id}", 10);
                if ($lock->get()) {
                    $orderedDetails = $ingredient->pivot;
                    #considering that all product ingredients are stored in gram.
                    $caclulatedOrderdWeightInGram = ($orderedDetails->weight) * $this->quantity;
                    $caclulatedOrderdWeightInKilogram = ($caclulatedOrderdWeightInGram / 1000);
                    $remainingWeightInKilogram = ($ingredient->remaining_weight) - $caclulatedOrderdWeightInKilogram; 
                    $ingredient->update(['remaining_weight' => $remainingWeightInKilogram]);
                    $ingredientService->disableProductsIfIngredientStockNotAvailable($ingredient);
                    $lock->release();
                }
    
                $this->notifyIfLowStockAndEmailNotSent($ingredient);
            }
        } catch(\Exception $exception) {
            Log::error('Exception in processing DecrementProductIngredientStockJob', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTrace()
            ]);
            throw $exception;
        }
    }

    private function notifyIfLowStockAndEmailNotSent(Ingredient $ingredient): void
    {
        if ($ingredient->isLowStockAndEmailNotSent) {
            SendLowStockEmailNotificationJob::dispatch($ingredient, [
                'recipient' => 'admin@inventory.com',
                'subject' => 'Low stock notification', 
                'message' => "Ingredient: {$ingredient->name} with ID: {$ingredient->id} is now nearly 50% of stock."
            ]);
        }
    }
}
