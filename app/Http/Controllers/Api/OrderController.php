<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Order\OrderService;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Order\CreateOrderRequest;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService  = $orderService;
    }

    public function create(CreateOrderRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $order = $this->orderService->createOrder($data['products']);
            DB::commit();

            Log::info('Order Placed Successfully!', ['order_id' => $order->id]);
            return response()->json(['message' => 'Order Placed Successfully!', 'order_id' => $order->id], Response::HTTP_OK);
        } catch(\Exception $exception) {
            Log::error('Exception while creating order', ['message' => $exception->getMessage(), 'trace' => $exception->getTrace()]);
            return response()->json(['message' => 'Something went wrong while creating order'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
