<?php

namespace App\Http\Controllers;

use App\Helpers\UnifiedJsonResponse;
use App\Http\Requests\Trip\ListTripsRequest;
use App\Repositories\BusRepository;
use App\Repositories\TripRepository;
use Illuminate\Http\Request;

class TripsController extends Controller
{
    public function list(ListTripsRequest $request, TripRepository $tripRepository)
    {
        $trips = $tripRepository->listPaginated($request->all());
        return UnifiedJsonResponse::success(['trips' => $trips]);
    }
}
