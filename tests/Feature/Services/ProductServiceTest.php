<?php

namespace Tests\Feature\Services;

use App\Models\Product;
use App\Services\Product\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProductService $productService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productService = new ProductService();
    }

    public function test_returns_correct_products()
    {
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();
        $product3 = Product::factory()->create();
        
        $requestedIds = [$product1->id, $product3->id];

        $result = $this->productService->getByIds($requestedIds);

        $this->assertCount(2, $result);
        $this->assertTrue($result->contains($product1));
        $this->assertTrue($result->contains($product3));
        $this->assertFalse($result->contains($product2));
    }

    public function test_returns_empty_collection()
    {
        $nonExistentIds = [1, 2];
        $result = $this->productService->getByIds($nonExistentIds);

        $this->assertCount(0, $result);
        $this->assertTrue($result->isEmpty());
    }
}
