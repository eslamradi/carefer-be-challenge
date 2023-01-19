<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\Discount;
use App\Models\Seat;
use App\Models\Slot;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCaseWithAcceptJson;

class CreateOrderTest extends TestCaseWithAcceptJson
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_order_flow()
    {
        $bus = Bus::factory()->create([
            'price' => 100
        ]);

        $slot = Slot::factory()->create([
            'bus_id' => $bus->id
        ]);

        $seats = Seat::factory()->count(10)->create([
            'bus_id' => $bus->id
        ]);

        $discount = Discount::factory()->create([
            'seats_count' => 2,
            'percentage' => 10,
            'amount' => 0,
            'max_amount' => 0
        ]);

        $user = User::factory()->customer()->create();

        $this->actingAs($user);

        $response = $this->post('/api/session/start', [
            'slot_id' => $slot->id
        ]);

        $response = $this->post('/api/order', [
           'slot_id' =>$slot->id,
           'seats' => [
            $seats->get(0)->id,
            $seats->get(1)->id,
           ]
        ]);

        $response->assertStatus(201);
        $response->assertJson(
                fn (AssertableJson $json) =>
                $json->has('data.order')
                    ->has('data.tickets')
                    ->where('data.order.discount', 20)
                    ->etc()
            );
        ;

        $response = $this->get("/api/bus/slot/{$slot->id}/available", [
            'slot_id' =>$slot->id,
         ]);

        $response->assertStatus(200)
            ->assertJsonCount(8, $key = 'data.seats');
    }
}
