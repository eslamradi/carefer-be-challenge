<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use HasFactory;
    use SoftDeletes;

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

    protected $with = [
        'startDestination',
        'endDestination',
    ];

    public function buses()
    {
        return $this->hasMany(Bus::class);
    }

    public function slots()
    {
        return $this->hasMany(\App\Models\Slot::class);
    }

    public function startDestination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function endDestination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function title()
    {
        return "{$this->startDestination->title}-{$this->endDestination->title}";
    }
}
