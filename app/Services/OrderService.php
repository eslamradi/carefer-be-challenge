<?php

namespace App\Services;

use App\Exceptions\InvalidSeatIdException;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderSeat;
use App\Models\Session;
use App\Models\Slot;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function startSession($userId, $slotId)
    {
        return $session = Session::create([
            'slot_id' => $slotId,
            'user_id' => $userId
        ]);
    }

    public function createOrder($user, $data)
    {
        DB::beginTransaction();

        try {
            $slot = Slot::with(['bus', 'bus.trip'])->find($data['slot_id']);

            $availableSeats = $slot->availableSeats()->whereIn('id', $data['seats'])->get();

            if ($availableSeats->count() != count($data['seats'])) {
                throw new InvalidSeatIdException('Some seats are not available, Double check selected seats for availablitiy');
            }

            $total = $slot->bus->price * $availableSeats->count();

            $discounts = Discount::where('seats_count', '<=', $availableSeats->count())->get();

            [$discount, $total] = $this->applyDiscounts($total, $discounts);

            $order = Order::create([
                'user_id' => $user->id,
                'trip_id' => $slot->bus->trip_id,
                'bus_id' => $slot->bus_id,
                'slot_id' => $slot->id,
                'time' => $slot->time,
                'date' => $slot->getNextAvailableDate(),
                'total' => $total,
                'discount' => $discount
            ]);

            $seats = [];
            $tickets = [];

            foreach ($availableSeats as $seat) {
                $seats[] = [
                    'order_id' => $order->id,
                    'seat_id' => $seat->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];

                $tickets[] = [
                    'order_id' => $order->id,
                    'trip' => $slot->bus->trip->title(),
                    'bus' => $slot->bus->title,
                    'seat' => $seat->title,
                    'user' => $user->email,
                    'date' => $order->date,
                    'time' => $order->time
                ];
            }

            OrderSeat::insert($seats);

            DB::commit();

            return [$order, $tickets];
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    private function applyDiscounts($total, $discounts)
    {
        $totalDiscount = 0;
        if ($discounts->isEmpty()) {
            return [$totalDiscount, $total];
        }

        foreach ($discounts as $discount) {
            $discountedAmount = 0;
            if ($discount->percentage) {
                $discountedAmount += $total * $discount->percentage / 100;
            }

            if ($discount->amount) {
                $discountedAmount += $total + $discount->amount;
            }

            if ($discount->max_amount && $discountedAmount > $discount->max_amount) {
                $discountedAmount = $discount->max_amount;
            }
            $totalDiscount += $discountedAmount;
        }

        return [$totalDiscount, ($total - $totalDiscount)];
    }
}
