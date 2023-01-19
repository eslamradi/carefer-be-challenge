<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Bus;
use App\Models\Destination;
use App\Models\Discount;
use App\Models\Seat;
use App\Models\Trip;
use App\Models\Slot;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::beginTransaction();

        try {
            if (Destination::count() == 0) {
                $cairo = Destination::create([
                    'title' => 'Cairo'
                ]);

                $alexandria = Destination::create([
                    'title' => 'Alexandria'
                ]);

                $aswan = Destination::create([
                    'title' => 'Aswan'
                ]);

                if (Trip::count() == 0) {
                    $shortTrip = Trip::create([
                        'start_destination_id' => $cairo->id,
                        'end_destination_id' => $alexandria->id,
                        'distance' => 90
                    ]);


                    $longTrip = Trip::create([
                        'start_destination_id' => $cairo->id,
                        'end_destination_id' => $aswan->id,
                        'distance' => 150
                    ]);
                }
                if (Bus::count() == 0) {
                    $shortTripBus = $shortTrip->buses()->create([
                        'title' => 'Short Trip Bus',
                        'price' => 180
                    ]);

                    $longTripBus = $longTrip->buses()->create([
                        'title' => 'Long Trip Bus',
                        'price' => 100
                    ]);

                    Slot::factory()->count(2)->create([
                        'bus_id' => $shortTripBus->id
                    ]);
                    Slot::factory()->count(2)->create([
                        'bus_id' => $longTripBus->id
                    ]);


                    if (Seat::count() == 0) {
                        $seats = [];
                        for ($i = 1; $i <= 10; $i++) {
                            $seats[] = [
                            'title' => "A$i"
                        ];
                        }
                        for ($i = 1; $i <= 10; $i++) {
                            $seats[] = [
                                'title' => "B$i"
                            ];
                        }
                        $longTripBus->seats()->createMany($seats);

                        $shortTripBus->seats()->createMany($seats);
                    }
                }
            }

            if (Discount::count() == 0) {
                Discount::create([
                    'title' => '5MORE',
                    'seats_count' => 5,
                    'percentage' => 10,
                    'amount' => 0,
                    'max_amount' => 100,
                ]);
            }


            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }
}
