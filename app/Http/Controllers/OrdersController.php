<?php

namespace App\Http\Controllers;

use App\Helpers\UnifiedJsonResponse;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function list(OrderRepository $orderRepository)
    {
        $orders = $orderRepository->list()->paginate(10);

        return UnifiedJsonResponse::success(['orders' => $orders]);
    }

    public function show($id, OrderRepository $orderRepository)
    {
        $order = $orderRepository->getById($id);
        $order->load(['user', 'trip', 'bus', 'seats', 'discounts']);
        
        return UnifiedJsonResponse::success(['order' => $order]);
    }


    public function delete($id, OrderRepository $orderRepository)
    {
        $order = $orderRepository->softDelete($id);

        return UnifiedJsonResponse::success([], __('Order Deleted Successfuly'));
    }
}
