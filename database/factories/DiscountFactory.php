<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Discount;

class DiscountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Discount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(4),
            'seats_count' => $this->faker->numberBetween(-10000, 10000),
            'percentage' => $this->faker->randomFloat(2, 0, 999999.99),
            'amount' => $this->faker->randomFloat(2, 0, 999999.99),
            'max_amount' => $this->faker->randomFloat(2, 0, 999999.99),
        ];
    }
}
