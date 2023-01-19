<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderDiscount;

class OrderDiscountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderDiscount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'order_id' => Order::factory(),
            'discount_id' => Discount::factory(),
            'amount' => $this->faker->randomFloat(2, 0, 999999.99),
        ];
    }
}
