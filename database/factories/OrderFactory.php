<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bus;
use App\Models\Order;
use App\Models\Slot;
use App\Models\Trip;
use App\Models\User;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $orderAmount = $this->faker->randomFloat(2, 0, 5000);
        return [
            'user_id' => User::factory(),
            'trip_id' => Trip::factory(),
            'bus_id' => Bus::factory(),
            'slot_id' => Slot::factory(),
            'time' => $this->faker->time(),
            'date' => $this->faker->date(),
            'total' => $orderAmount,
            'discount' => $this->faker->randomFloat(2, 0, $orderAmount / 2),
        ];
    }
}
