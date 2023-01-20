<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slot extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'time',
        'day_of_week',
        'bus_id'
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function getNextAvailableDate()
    {
        $today = Carbon::now();
        $todaysDayOfWeek = $today->dayOfWeek;

        if ($this->day_of_week > $todaysDayOfWeek) {
            $diff = $this->day_of_week - $todaysDayOfWeek;
        } elseif ($this->day_of_week == $todaysDayOfWeek) {
            if ($today->isBefore(Carbon::parse($this->time))) {
                return $today->toDateString();
            } else {
                return $today->addWeek()->toDateString();
            }
        } else {
            $diff = (7 + $this->day_of_week) - $todaysDayOfWeek;
        }

        return $today->addDays($diff)->toDateString();
    }

    public function session()
    {
        return $this->hasMany(Session::class);
    }

    public function availableSeats()
    {
        $nextAvilableDate = $this->getNextAvailableDate();
        $orderIds = Order::select('id')->where([
            'bus_id' => $this->bus_id,
            'slot_id' => $this->id,
            'date' => $nextAvilableDate
        ])->get();

        $takenSeatsIds = OrderSeat::select('seat_id')->whereIn('order_id', $orderIds)->get();

        Seat::whereNotIn('id', $takenSeatsIds)->where('bus_id', $this->bus_id)->get();

        return Seat::whereNotIn('id', $takenSeatsIds)->where('bus_id', $this->bus_id);
    }
}
