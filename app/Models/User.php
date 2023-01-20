<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * check if user has the specified role
     *
     * @return boolean
     */
    public function hasRole($role)
    {
        return $this->role == $role;
    }


    public function scopeWithFrequentBook()
    {
        /***
            SELECT u.id, u.email, (
                SELECT
                    CONCAT(sd.title, '-', ed.title)
                FROM orders o
                    LEFT JOIN trips t on t.id = o.trip_id
                    LEFT JOIN destinations sd on sd.id = t.start_destination_id
                    LEFT JOIN destinations ed on ed.id = t.end_destination_id
                where o.user_id = u.id
                GROUP BY o.trip_id
                ORDER BY
                    count(o.trip_id) DESC
                LIMIT
                    1 -- )
            ) as 'frequentBook'
            FROM users u
        */


        $subQuery = DB::table('orders AS o')
                    ->select(DB::raw("CONCAT(sd.title, '-', ed.title)"))
                    ->leftJoin('trips AS t', 't.id', '=', 'o.trip_id')
                    ->leftJoin('destinations AS sd', 'sd.id', '=', 't.start_destination_id')
                    ->leftJoin('destinations AS ed', 'ed.id', '=', 't.end_destination_id')
                    ->whereRaw('o.user_id = users.id')
                    ->groupBy('o.trip_id')
                    ->orderBy(DB::raw('count(o.trip_id)'), 'desc')
                    ->limit(1);

        /** using "users.id, users.email" to follow the documentaion,
         *  and could replace  with "users.*" to mitigate normal eloquent
         *  model with all parameters
         */
        $query = $this->selectRaw("users.id, users.email, ({$subQuery->toSql()}) as 'frequentBook'")->where('role', Role::CUSTOMER);

        return $query;
    }

    public function scopeCustomer()
    {
        return $this->where('role', Role::CUSTOMER);
    }

    public function scopeAdmin()
    {
        return $this->where('role', Role::ADMIN);
    }

    public function orders()
    {
        return $this->hasmany(Order::class);
    }
}
