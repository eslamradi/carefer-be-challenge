<?php

namespace Database\Seeders;

use App\Models\OrderSeat;
use Illuminate\Database\Seeder;

class OrderSeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderSeat::factory()->count(5)->create();
    }
}
