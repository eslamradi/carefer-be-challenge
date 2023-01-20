<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderDiscount;
use App\Models\OrderSeat;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    /**
     * get an order by its ID
     *
     * @param integer $id
     * @return \App\Models\Order
     */
    public function getById(int $id)
    {
        return Order::findOrFail($id);
    }

    /**
     * return a query builder to list all orders
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function list()
    {
        return Order::latest();
    }

    /**
     * sof delete order by id
     *
     * @param int $id
     * @return void
     */
    public function softDelete(int $id)
    {
        DB::beginTransaction();
        try {
            $order = Order::findOrFail($id);

            $order->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }
}
