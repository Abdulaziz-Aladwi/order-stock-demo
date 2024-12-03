<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Order\OrderService;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Services\Order\OrderJobsDispatcherService;

class OrderController extends Controller
{
    protected OrderService $orderService;
    protected OrderJobsDispatcherService $orderJobsDispatcherService;

    public function __construct(OrderService $orderService, OrderJobsDispatcherService $orderJobsDispatcherService)
    {
        $this->orderService  = $orderService;
        $this->orderJobsDispatcherService  = $orderJobsDispatcherService;
    }

    public function create(CreateOrderRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $order = $this->orderService->createOrder($data['products']);
            DB::commit();
            Log::info('Order Placed Successfully!', ['order_id' => $order->id]);
            $this->orderJobsDispatcherService->dispatchForStockUpdate($order);
            return response()->json(['message' => 'Order Placed Successfully!', 'order_id' => $order->id], Response::HTTP_OK);
        } catch(\Exception $exception) {
            DB::rollBack();
            Log::error('Exception while creating order', ['message' => $exception->getMessage(), 'trace' => $exception->getTrace()]);
            return response()->json(['message' => 'Something went wrong while creating order'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
