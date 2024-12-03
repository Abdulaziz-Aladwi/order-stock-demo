<?php 

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public function getByIds(array $ids): Collection
    {
        return Product::whereIn('id', $ids)->get();
    }    
}
