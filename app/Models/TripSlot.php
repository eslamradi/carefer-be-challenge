<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripSlot extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'trip_id',
        'time',
        'day_of_week'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'trip_id' => 'integer',
        'time' => 'time',
        'day_of_week' => 'integer'
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
