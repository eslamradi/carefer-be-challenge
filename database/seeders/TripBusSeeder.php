<?php

namespace Database\Seeders;

use App\Models\TripBus;
use Illuminate\Database\Seeder;

class TripBusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TripBus::factory()->count(5)->create();
    }
}
