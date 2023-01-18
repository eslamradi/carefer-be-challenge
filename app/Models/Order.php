<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'trip_id',
        'bus_id',
        'slot_id',
        'time',
        'date',
        'total',
        'discount',
    ];

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }
}
