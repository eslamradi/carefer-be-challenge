<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bus;
use App\Models\Trip;
use App\Models\TripBus;

class TripBusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TripBus::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'trip_id' => Trip::factory(),
            'bus_id' => Bus::factory(),
            'price' => $this->faker->randomFloat(2, 0, 999999.99),
        ];
    }
}
