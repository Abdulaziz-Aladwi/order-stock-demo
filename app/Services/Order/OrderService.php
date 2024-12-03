<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Models\Product;
use App\Services\Product\ProductService;

class OrderService
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function createOrder(array $orderProducts): Order
    {
        $orderProductsIds = array_column($orderProducts, 'product_id');
        $products = $this->productService->getByIds($orderProductsIds)->toArray();
  
        $orderDataWithProducts = array_map(function($orderProductsElement, $productsElement) {
            return array_merge($orderProductsElement, $productsElement);
        }, $orderProducts, $products);

        $orderTotalAmount = 0;
        foreach ($orderDataWithProducts as $orderDataWithProduct) {
            $orderTotalAmount += ($orderDataWithProduct['price']) * ($orderDataWithProduct['quantity']);
        }

        $order = Order::create(['total_amount' => $orderTotalAmount]);
        $this->createOrderProducts($order, $orderDataWithProducts);

        return $order;
    }

    public function createOrderProducts(Order $order, array $orderDataWithProducts): void
    {
        foreach($orderDataWithProducts as $orderDataWithProduct) {
            $order->products()->attach([
                $orderDataWithProduct['product_id'] => ['quantity' => $orderDataWithProduct['quantity'], 'price' => $orderDataWithProduct['price']]
            ]);
        }
    }
}
