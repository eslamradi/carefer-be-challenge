<?php

namespace App\Http\Controllers;

use App\Helpers\UnifiedJsonResponse;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function list(UserRepository $userRepository)
    {
        $customers = $userRepository->customersWithFrequentBook()->paginate(10);

        return UnifiedJsonResponse::success(['customers' => $customers]);
    }
}
