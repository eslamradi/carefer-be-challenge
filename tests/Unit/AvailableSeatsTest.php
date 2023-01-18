<?php

namespace Tests\Unit;

use App\Models\Bus;
use App\Models\Order;
use App\Models\OrderSeat;
use App\Models\Seat;
use App\Models\Slot;
use App\Models\Trip;
use App\Repositories\BusRepository;
use Carbon\Carbon;
use Database\Factories\TripFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCaseWithAcceptJson;

class AvailableSeatsTest extends TestCaseWithAcceptJson
{
    use DatabaseTransactions;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_some_seats_available()
    {
        $bus = Bus::factory()->create();

        $trip = Trip::factory()->create();

        $slot = Slot::factory()->create([
            'bus_id' => $bus->id,
        ]);

        $seats = Seat::factory()->count(10)->create([
            'bus_id' => $bus->id
        ]);

        $busRepository = new BusRepository();

        $availableSeats = $busRepository->getAvailableSeats($bus->id, $slot->id);
        $this->assertTrue($availableSeats->count() == 10);

        $order = Order::factory()->create([
            'bus_id' => $bus->id,
            'trip_id' => $trip->id,
            'slot_id' => $slot->id,
            'date' => $slot->getNextAvailableDate()
        ]);

        OrderSeat::create([
            'order_id' => $order->id,
            'seat_id' => $seats->get(0)->id
        ]);

        OrderSeat::create([
            'order_id' => $order->id,
            'seat_id' => $seats->get(5)->id
        ]);

        $availableSeats = $busRepository->getAvailableSeats($bus->id, $slot->id);
        $this->assertTrue($availableSeats->count() == 8);

        for ($i=0; $i < 3; $i++) {
            $order = Order::factory()->create([
                'bus_id' => $bus->id,
                'trip_id' => $trip->id,
                'slot_id' => $slot->id,
                'date' => Carbon::parse($slot->getNextAvailableDate())->subDays(14)
            ]);

            OrderSeat::create([
                'order_id' => $order->id,
                'seat_id' => $seats->get(0)->id
            ]);

            OrderSeat::create([
                'order_id' => $order->id,
                'seat_id' => $seats->get(3)->id
            ]);

            OrderSeat::create([
                'order_id' => $order->id,
                'seat_id' => $seats->get(5)->id
            ]);
        }

        $availableSeats = $busRepository->getAvailableSeats($bus->id, $slot->id);
        $this->assertTrue($availableSeats->count() == 8);
    }
}
