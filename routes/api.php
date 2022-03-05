<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;

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
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::get('/room/{date}/', [RoomController::class, 'getFreeRoomsPerDate']);

/**
 * Authorized users only
 */
Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::resource('reservation', ReservationController::class);
    Route::resource('user',UserController::class);
    Route::get('/room', [RoomController::class, 'getByPeriod']);
});

