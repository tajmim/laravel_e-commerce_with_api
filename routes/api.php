<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ManagerController;
use App\Http\Controllers\API\SellerController;


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



// admin start......................................../

Route::group(['prefix'=>'admin'],function($router){
    Route::post('login',[AdminController::class, 'login']);
    Route::post('register',[AdminController::class, 'register']);

});
Route::group(['middleware'=>['jwt.role:admin','jwt.auth'],'prefix'=>'admin'],function($router){
    Route::post('logout',[AdminController::class, 'logout']);
    Route::get('profile_details',[AdminController::class, 'profile_details']);
    Route::post('create_manager',[AdminController::class, 'create_manager']);
    Route::post('create_user',[AdminController::class, 'create_user']);
    Route::get('show_all_user',[AdminController::class, 'show_all_user']);
    Route::get('show_user/{id}',[AdminController::class, 'show_user']);
    Route::get('delete_user/{id}',[AdminController::class, 'delete_user']);

    Route::get('show_all_order',[AdminController::class, 'show_all_order']);
    Route::get('show_all_seller',[AdminController::class, 'show_all_seller']);
    Route::get('show_seller/{id}',[AdminController::class, 'show_seller']);
    Route::get('delete_seller_product/{id}',[AdminController::class, 'delete_seller_product']);
//     Route::post('fix_percentage/{id}',[AdminController::class, 'fix_percentage']);



 });


// user start.................................................../

Route::group(['prefix'=>'user'],function($router){
    Route::post('login',[UserController::class, 'login']);
    Route::post('register',[UserController::class, 'register']);
    Route::post('contact',[UserController::class, 'contact']);

});
Route::group(['middleware'=>['jwt.role:user','jwt.auth'],'prefix'=>'user'],function($router){
    Route::post('logout',[UserController::class, 'logout']);
    Route::get('profile_details',[UserController::class, 'profile_details']);

    Route::get('show_product',[UserController::class, 'show_product']);
    Route::get('product_details/{id}',[UserController::class, 'product_details']);
    // Route::post('add_cart/{id}',[UserController::class, 'add_cart']);
    // Route::get('show_cart',[UserController::class, 'add_cart']);
    // Route::get('delete_cart/{id}',[UserController::class, 'add_cart']);
    // Route::get('add_wish/{id}',[UserController::class, 'add_wish']);
    // Route::get('show_wish',[UserController::class, 'add_wish']);
    // Route::get('delete_wish/{id}',[UserController::class, 'delete_wish']);
    // Route::post('make_order',[UserController::class, 'make_order']);
    // Route::get('show_order',[UserController::class, 'show_order']);
    // Route::post('add_review',[UserController::class, 'add_review']);
});





// manager start............................../

Route::group(['prefix'=>'manager'],function($router){
    Route::post('login',[ManagerController::class, 'login']);
    Route::post('register',[ManagerController::class, 'register']);

});
Route::group(['middleware'=>['jwt.role:manager','jwt.auth'],'prefix'=>'manager'],function($router){
    Route::post('logout',[ManagerController::class, 'logout']);
    Route::get('profile_details',[ManagerController::class, 'profile_details']);
    Route::post('edit_profile',[ManagerController::class, 'edit_profile']);
    
    Route::get('show_category',[ManagerController::class, 'show_category']);
    Route::post('add_category',[ManagerController::class, 'add_category']);
    Route::get('delete_category/{id}',[ManagerController::class, 'delete_category']);

    Route::post('add_product',[ManagerController::class, 'add_product']);
    Route::post('add_variation/{id}',[ManagerController::class, 'add_variation']);
    Route::get('show_product',[ManagerController::class, 'show_product']);
    Route::post('edit_product/{id}',[ManagerController::class, 'edit_product']);
    Route::post('edit_variation/{id}',[ManagerController::class, 'edit_variation']);
    Route::get('delete_product/{id}',[ManagerController::class, 'delete_product']);
    // Route::get('view_order',[ManagerController::class, 'view_order']);
    // Route::get('accept_order/{id}',[ManagerController::class, 'accept_order']);
    // Route::get('cancel_order/{id}',[ManagerController::class, 'cancel_order']);


    // Route::get('show_all_order',[ManagerController::class, 'show_all_order']);
    // Route::get('show_all_seller',[ManagerController::class, 'show_all_seller']);
    // Route::get('show_seller/{id}',[ManagerController::class, 'show_seller']);
    // Route::get('delete_seller_product/{id}',[ManagerController::class, 'delete_seller_product']);
    // Route::get('recieve_payment/{id}',[ManagerController::class, 'recieve_payment']);
});



// seller start......................................./

Route::group(['prefix'=>'seller'],function($router){
    Route::post('login',[SellerController::class, 'login']);
    Route::post('register',[SellerController::class, 'register']);

});
Route::group(['middleware'=>['jwt.role:seller','jwt.auth'],'prefix'=>'seller'],function($router){
    Route::post('logout',[SellerController::class, 'logout']);
    Route::get('profile_details',[SellerController::class, 'profile_details']);
    Route::post('edit_profile',[SellerController::class, 'edit_profile']);

    Route::get('show_category',[SellerController::class, 'show_category']);
    Route::post('add_category',[SellerController::class, 'add_category']);
    Route::get('delete_category/{id}',[SellerController::class, 'delete_category']);

    Route::post('add_product',[SellerController::class, 'add_product']);
    Route::post('add_variation/{id}',[SellerController::class, 'add_variation']);
    Route::get('show_product',[SellerController::class, 'show_product']);
    Route::post('edit_product/{id}',[SellerController::class, 'edit_product']);
    Route::get('delete_product/{id}',[SellerController::class, 'delete_product']);
    
    Route::get('view_order',[SellerController::class, 'view_order']);
    Route::get('accept_order/{id}',[SellerController::class, 'accept_order']);
    Route::get('cancel_order/{id}',[SellerController::class, 'cancel_order']);
});