<?php

namespace Tests\Unit;

use App\Models\Slot;
use Carbon\Carbon;
use Tests\TestCase as TestsTestCase;

class NextSlotDateTest extends TestsTestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_slot_before_current_day_of_week()
    {
        $slotDate = Carbon::now()->addDays(2);

        $slot = Slot::factory()->create([
            'day_of_week' => $slotDate->dayOfWeek
        ]);

        $this->assertTrue($slotDate->toDateString() == $slot->getNextAvailableDate());
    }

    public function test_slot_after_current_day_of_week()
    {
        $slotDate = Carbon::now()->subDays(2);

        $slot = Slot::factory()->create([
            'day_of_week' => $slotDate->dayOfWeek
        ]);

        $this->assertTrue($slotDate->addWeek()->toDateString() == $slot->getNextAvailableDate());
    }

    public function test_slot_today_due()
    {
        $slotDate = Carbon::now();

        $slot = Slot::factory()->create([
            'day_of_week' => $slotDate->dayOfWeek,
            'time' => $slotDate->subHour()->toTimeString()
        ]);
        $this->assertTrue($slotDate->addWeek()->toDateString() == $slot->getNextAvailableDate());
    }

    public function test_slot_today_not_due()
    {
        $slotDate = Carbon::now();

        $slot = Slot::factory()->create([
            'day_of_week' => $slotDate->dayOfWeek,
            'time' => $slotDate->addHour()->toTimeString()
        ]);
        $this->assertTrue($slotDate->toDateString() == $slot->getNextAvailableDate());
    }
}
