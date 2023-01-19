<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BusesController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\TripsController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\ValidateBookingSession;
use App\Models\Role;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('me', 'me');
});

Route::controller(TripsController::class)->group(function () {
    Route::get('trip', 'list');
});

Route::controller(BusesController::class)->group(function () {
    Route::get('trip/{tripId}/buses', 'list');
    Route::get('bus/slot/{slotId}/available', 'getAvailableSeats');
});

Route::controller(BookingController::class)->group(function () {
    Route::middleware(['auth:api', ValidateBookingSession::class])->group(function () {
        Route::post('session/start', 'startSession')->name('session.start');
        Route::post('order', 'order');
    });
});

Route::middleware(['auth:api', "role:".Role::ADMIN])->group(function () {
    Route::controller(CustomerController::class)->group(function () {
        Route::get('customer', 'list');
    });

    Route::controller(OrdersController::class)->group(function () {
        Route::get('order', 'list');
        Route::get('order/{id}', 'show');
        Route::delete('order/{id}', 'delete');
    });
});
