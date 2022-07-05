<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RegisterdriverController;

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
//Route::post('register','RegisterController@register');
Route::post('login','RegisterController@login');
Route::middleware('auth:api')->post('logout','RegisterController@logout');


Route::post('drivers/{driver_id}/comments/store','CommentController@store');
    Route::get('drivers/{driver_id}/comments','CommentController@list');

   // Route::post('register/driver/{admin_id}','RegisterdriverController@register');
   Route::post('login/driver','RegisterdriverController@login');
   Route::middleware('auth:driver')->post('logout/driver','RegisterdriverController@logout');

   Route::group([

    'middleware' => 'api',


], function ($router) {

    Route::group([ 'prefix' => 'admin', ], function ($router) {
        Route::post('register',[CustomAuthController::class,'register']);
        Route::post('login',[CustomAuthController::class,'login']);
        Route::post('logout',[CustomAuthController::class,'logout']);


    });
    Route::group([ 'prefix' => 'user', ], function ($router) {
        Route::post('register',[RegisterController::class,'register']);
        Route::post('login',[RegisterController::class,'login']);
        Route::post('logout',[RegisterController::class,'logout']);
    });

});

   // Route::post('register/driver/{admin_id}','RegisterdriverController@register');

    Route::get('profile/{driver_id}','RegisterdriverController@getprofile');
    Route::post('profile/update','RegisterdriverController@updateprofile');

    Route::group([
        'middleware' => 'App\Http\Middleware\Admin:admin-api',
        'prefix' => 'just_admin',

    ], function () {


        Route::post('register',[RegisterdriverController::class,'register']);

    });
    Route::get('profile',function(){
        return 'unautheantic user ';
    })->name('login');

   //Route::post('register/admin','CustomAuthController@register');
  // Route::post('login/admin','CustomAuthController@login');

//});
