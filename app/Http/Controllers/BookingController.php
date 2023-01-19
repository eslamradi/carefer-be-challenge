<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidSeatIdException;
use App\Helpers\UnifiedJsonResponse;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\SessionRequest;
use App\Models\Session;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Throwable;

class BookingController extends Controller
{
    public function startSession(SessionRequest $request, OrderService $orderService)
    {
        $session = $orderService->startSession(auth()->user()->id, $request->input('slot_id'));

        return UnifiedJsonResponse::success([
            'session' => [
                'id' => $session->uuid,
                'duration' => Session::DURATION
                ]
        ], __('Session Started Successfuly'), 201);
    }

    public function order(CreateOrderRequest $request, OrderService $orderService)
    {
        try {
            [$order, $tickets] = $orderService->createOrder(auth()->user(), $request->validated());

            return UnifiedJsonResponse::success([
                'order' => $order,
                'tickets' => $tickets,
            ], __('Order Created Successfully'), 201);
        } catch(InvalidSeatIdException $e) {
            return UnifiedJsonResponse::error([], $e->getMessage(), 400);
        } catch(Throwable $e) {
            return UnifiedJsonResponse::error([], $e->getMessage(), 500);
        }
    }
}
