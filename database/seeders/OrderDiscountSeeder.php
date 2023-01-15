<?php

namespace Database\Seeders;

use App\Models\OrderDiscount;
use Illuminate\Database\Seeder;

class OrderDiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderDiscount::factory()->count(5)->create();
    }
}
