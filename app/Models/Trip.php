<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'start_destination_id',
        'end_destination_id',
        'distance',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'start_destination_id' => 'integer',
        'end_destination_id' => 'integer',
    ];

    public function buses()
    {
        return $this->hasMany(Bus::class);
    }

    public function slots()
    {
        return $this->hasMany(\App\Models\TripSlot::class);
    }

    public function startDestination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function endDestination()
    {
        return $this->belongsTo(Destination::class);
    }
}
