<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register',[ApiController::class,'register']);
Route::post('/user_login',[ApiController::class,'user_login']);


Route::post('/seller/register',[ApiController::class,'seller_register']);
Route::post('/seller/login',[ApiController::class,'seller_login']);


Route::get('show_products',[ApiController::class,'show_products']);




Route::post('place_order/{id}',[ApiController::class,'place_order']);


