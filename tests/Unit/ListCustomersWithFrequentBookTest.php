<?php

namespace Tests\Unit;

use App\Models\Bus;
use App\Models\Seat;
use App\Models\Slot;
use App\Models\Trip;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ListCustomersWithFrequentBookTest extends TestCase
{
    // use DatabaseTransactions;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_frequent_book_query()
    {
        $user = User::factory()->customer()->create();

        $trip = Trip::factory()->create();

        $bus = Bus::factory()->create([
            'price' => 100,
            'trip_id' => $trip->id
        ]);

        $slot = Slot::factory()->create([
            'bus_id' => $bus->id
        ]);
        $seat = Seat::factory()->create([
            'bus_id' => $bus->id
        ]);

        $test = User::withFrequentBook()->where('id', $user->id)->first();
        $this->assertTrue($test->frequentBook == null);

        $orderService = new OrderService();

        $orderService->createOrder($user, [
            'slot_id' => $slot->id,
            'seats' => [
                $seat->id
            ]
        ]);

        $test = User::withFrequentBook()->where('id', $user->id)->first();

        $this->assertTrue($test->frequentBook == $trip->title());

        $otherTrip = Trip::factory()->create();

        $otherBus = Bus::factory()->create([
            'price' => 100,
            'trip_id' => $otherTrip->id
        ]);

        $otherSlot = Slot::factory()->create([
            'bus_id' => $otherBus->id
        ]);
        $otherSeats = Seat::factory()->count(2)->create([
            'bus_id' => $otherBus->id
        ]);

        $orderService->createOrder($user, [
            'slot_id' => $otherSlot->id,
            'seats' => [
                $otherSeats->get(0)->id
            ]
        ]);

        $orderService->createOrder($user, [
            'slot_id' => $otherSlot->id,
            'seats' => [
                $otherSeats->get(1)->id
            ]
        ]);

        $secondTest = User::withFrequentBook()->where('id', $user->id)->first();
        $this->assertTrue($secondTest->frequentBook == $otherTrip->title());
    }
}
