<?php

namespace Database\Seeders;

use App\Models\TripSlot;
use Illuminate\Database\Seeder;

class SlotsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TripSlot::factory()->count(5)->create();
    }
}
