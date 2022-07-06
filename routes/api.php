<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RegisterdriverController;
use App\Http\Controllers\AdminAddedController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();


});



Route::post('drivers/{driver_id}/comments/store','CommentController@store');
    Route::get('drivers/{driver_id}/comments','CommentController@list');



   Route::group([

    'middleware' => 'api',


], function ($router) {

    Route::group([ 'prefix' => 'admin', ], function ($router) {
        Route::post('register',[CustomAuthController::class,'register']);
        Route::post('login',[App\Http\Controllers\CustomAuthController::class,'login']);
        Route::post('logout',[CustomAuthController::class,'logout']);


    });
    Route::group([ 'prefix' => 'user', ], function ($router) {
        Route::post('register',[RegisterController::class,'register']);
        Route::post('login',[RegisterController::class,'login']);
        Route::post('logout',[RegisterController::class,'logout']);
    });
    Route::group([ 'prefix' => 'driver', ], function ($router) {
        Route::post('login',[RegisterdriverController::class,'login']);
        Route::post('logout',[RegisterdriverController::class,'logout']);


    });

});



    Route::group([
        'middleware' => 'App\Http\Middleware\Admin:admin-api',
        'prefix' => 'just_admin',

    ], function () {


        Route::post('addDriver',[AdminAddedController::class,'addDriver']);

    });
    Route::group([
        'middleware' => 'App\Http\Middleware\DriverAuth:driver-api',
        'prefix' => 'just_driver',

    ], function () {


        Route::post('updatePro',[RegisterdriverController::class,'updatePro']);

    });
    Route::get('profile',function(){
        return 'unautheantic user ';
    })->name('login');

   //Route::post('register/admin','CustomAuthController@register');
  // Route::post('login/admin','CustomAuthController@login');

//});
