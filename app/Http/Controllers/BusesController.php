<?php

namespace App\Http\Controllers;

use App\Helpers\UnifiedJsonResponse;
use App\Repositories\BusRepository;
use App\Repositories\TripRepository;
use Illuminate\Http\Request;

class BusesController extends Controller
{
    public function list($tripId, TripRepository $tripRepository, BusRepository $busRepository)
    {
        $trip = $tripRepository->getById($tripId);
        if (! $trip) {
            return UnifiedJsonResponse::error([], __('Resource not found'), 404);
        }
        $buses = $busRepository->getListByTrip($trip);
        return UnifiedJsonResponse::success(['buses' => $buses]);
    }

    public function getAvailableSeats($slotId, BusRepository $busRepository)
    {
        $seats = $busRepository->getAvailableSeats($slotId);
        return UnifiedJsonResponse::success(['seats' => $seats]);
    }
}
