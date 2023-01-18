<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    public const DURATION = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'slot_id'
    ];

    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }

    /**
     * check if session is available for interactions
     *
     * @return boolean
     */
    public function isValid()
    {
        $startTime = Carbon::parse($this->created_at);
        $diff = $startTime->diffInMinutes(Carbon::now());

        return $diff < self::DURATION;
    }
}
