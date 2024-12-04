<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\Order\OrderService;
use App\Http\Controllers\Api\OrderController;
use App\Services\Order\OrderJobsDispatcherService;
use App\Http\Requests\Order\CreateOrderRequest;
use Mockery;
use Symfony\Component\HttpFoundation\Response;

class OrderControllerTest extends TestCase
{
    protected $orderService;
    protected $orderJobsDispatcherService;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->orderService = Mockery::mock(OrderService::class);
        $this->orderJobsDispatcherService = Mockery::mock(OrderJobsDispatcherService::class);
        
        $this->controller = new OrderController(
            $this->orderService,
            $this->orderJobsDispatcherService
        );
    }

    public function test_create_order_success()
    {
        $products = [
            ['product_id' => 1, 'quantity' => 2],
            ['product_id' => 2, 'quantity' => 1],
        ];
        
        $request = Mockery::mock(CreateOrderRequest::class);
        $request->shouldReceive('validated')
            ->once()
            ->andReturn(['products' => $products]);

        $order = new Order();
        $order->id = 1;

        $this->orderService->shouldReceive('createOrder')
            ->once()
            ->with($products)
            ->andReturn($order);

        $this->orderJobsDispatcherService->shouldReceive('dispatchForStockUpdate')
            ->once()
            ->with($order);

        Log::shouldReceive('info')->once();
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $response = $this->controller->create($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals([
            'message' => 'Order Placed Successfully!',
            'order_id' => 1
        ], json_decode($response->getContent(), true));
    }

    public function test_create_order_failure()
    {
        $request = Mockery::mock(CreateOrderRequest::class);
        $request->shouldReceive('validated')
            ->once()
            ->andThrow(new \Exception('Test exception'));

        Log::shouldReceive('error')->once();
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();

        $response = $this->controller->create($request);

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
        $this->assertEquals([
            'message' => 'Something went wrong while creating order'
        ], json_decode($response->getContent(), true));
    }
}
