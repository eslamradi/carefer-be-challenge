<?php

namespace App\Http\Controllers;

use App\Helpers\UnifiedJsonResponse;
use App\Http\Requests\SessionRequest;
use App\Models\Session;
use App\Services\OrderService;
use Illuminate\Http\Request;

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

    public function order()
    {
    }
}
