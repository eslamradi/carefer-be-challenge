<?php

namespace App\Services;

use App\Models\Session;

class OrderService
{
    public function startSession($userId, $slotId)
    {
        return $session = Session::create([
            'slot_id' => $slotId,
            'user_id' => $userId
        ]);
    }
}
