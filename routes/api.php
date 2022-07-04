<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('register','RegisterController@register');
Route::post('login','RegisterController@login');
Route::middleware('auth:api')->post('logout','RegisterController@logout');


Route::post('drivers/{driver_id}/comments/store','CommentController@store');
    Route::get('drivers/{driver_id}/comments','CommentController@list');

    Route::post('register/driver/{admin_id}','RegisterdriverController@register');
   Route::post('login/driver','RegisterdriverController@login');
   Route::middleware('auth:driver')->post('logout/driver','RegisterdriverController@logout');


  /* Route::prefix('admin')->middleware('Admin:admin')->group(function(){
    Route::post('register/driver/{admin_id}','RegisterdriverController@register');
    Route::post('login/driver','RegisterdriverController@login');
   }
);*/


    Route::get('profile/{driver_id}','RegisterdriverController@getprofile')->name('login');
    Route::post('profile/update/{driver_id}','RegisterdriverController@updateprofile');


   Route::post('register/admin','CustomAuthController@register');
   Route::post('login/admin','CustomAuthController@login');

//});
