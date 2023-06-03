<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

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

Route::post('/login',function(Request $request){
$user = User::find(1);
 
// Creating a token without scopes...
$token = $user->createToken('Token Name')->accessToken;
 
// Creating a token with scopes...
$token = $user->createToken('My Token', ['place-orders'])->accessToken;
return $token;
});