<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BusesController;
use App\Http\Controllers\TripsController;
use App\Http\Middleware\ValidateBookingSession;

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
