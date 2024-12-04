<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Product;
use App\Constants\ProductStatus;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_order_successfully()
    {
        $product = Product::factory()->create(['name' => 'Burger', 'status' => ProductStatus::ACTIVE]); 

        $payload = [
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2
                ]
            ]
        ];

        $response = $this->postJson('/api/order/create', $payload);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'message',
                'order_id'
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $response['order_id']
        ]);
    }

    public function test_fails_for_validation()
    {
        $product = Product::factory()->create(['name' => 'Burger', 'status' => ProductStatus::ACTIVE]); 

        $payload = [
            'products' => [
                [
                    'id' => $product->id,
                    'quantity' => 2
                ]
            ]
        ];

        $response = $this->postJson('/api/order/create', $payload);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
            ]);
    }    
}
