<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TripRepository
{
    /**
     * build the query for filtering and getting trips
     *
     * @param array $filters
     * @return Illuminate\Database\Query\Builder
     */
    protected function listQuery(array $filters)
    {
        $trips = Trip::query();
        if (!empty($filters['start_destination_id'])) {
            $trips = $trips->where('start_destination_id', $filters['start_destination_id']);
        }
        if (!empty($filters['end_destination_id'])) {
            $trips = $trips->where('end_destination_id', $filters['end_destination_id']);
        }

        return $trips;
    }

    /**
     * get trips list with pagination
     *
     * @param array $filters
     * @return Illuminate\Pagination\LengthAwarePaginator;
     */
    public function listPaginated(array $filters)
    {
        return $this->listQuery($filters)->paginate(5);
    }

    /**
     * Undocumented function
     *
     * @param array $filters
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function list(array $filters)
    {
        return $this->listQuery($filters)->get();
    }

    /**
     * fetch trip by id
     *
     * @param integer $id
     * @return Trip|null
     */
    public function getById(int $id)
    {
        return Trip::find($id);
    }
}
