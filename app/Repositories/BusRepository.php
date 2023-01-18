<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\Order;
use App\Models\OrderSeat;
use App\Models\Seat;
use App\Models\Slot;
use App\Models\Trip;
use Carbon\Carbon;

class BusRepository
{
    /**
     * get buses for a certain trip
     *
     * @param Trip $trip
     * @return void
     */
    public function getListByTrip(Trip $trip)
    {
        return $trip->buses()->get();
    }

    /**
     * get a bus by its ID
     *
     * @param integer $id
     * @return \App\Models\Bus
     */
    public function getById(int $id)
    {
        return Bus::find($id);
    }

    /**
     * get list of available seats to be booked
     *
     * @param int $busId
     * @param int $slotId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableSeats(int $busId, int $slotId)
    {
        $slot = Slot::findOrFail($slotId);
        $nextAvilableDate = $slot->getNextAvailableDate();
        $orderIds = Order::select('id')->where([
            'bus_id' => $busId,
            'slot_id' => $slotId,
            'date' => $nextAvilableDate
        ])->get();

        $takenSeatsIds = OrderSeat::select('seat_id')->whereIn('order_id', $orderIds)->get();

        return $availableSeats = Seat::whereNotIn('id', $takenSeatsIds)->where('bus_id', $busId)->get();
    }
}
