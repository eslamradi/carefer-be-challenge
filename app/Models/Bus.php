<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'trip_id',
        'price',
        'active',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'trip_id' => 'integer',
        'price' => 'float',
        'active' => 'boolean',
    ];

    protected $with = [
        'slots'
    ];

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function slots()
    {
        return $this->hasMany(Slot::class);
    }
}
