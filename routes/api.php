<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', 'API\AuthController@Login');
Route::post('register', 'API\AuthController@Register');
Route::post('logout', 'API\AuthController@logout');
Route::post('reset-password', 'API\AuthController@resetPassword');
Route::post('roles-type', 'API\AuthController@getRolesByType');
Route::post('roles', 'API\AuthController@getRoles');
//Route::post('roles', 'API\ConfigurationController@testPush');


Route::group(['middleware' => 'auth:api', 'namespace' => 'API'], function() {
    Route::post('update', 'AuthController@Update');
    Route::post('get-profile', 'AuthController@getProfile');
    Route::post('events', 'EventController@getItems');
    Route::post('event', 'EventController@getItem');

    Route::post('class-schedules', 'ClassScheduleController@getItems');
    Route::post('class-schedule', 'ClassScheduleController@getItem');

    Route::post('trainers', 'TrainerController@getitems');
    Route::post('trainer', 'TrainerController@getitem');
    Route::post('trainer/reviews', 'TrainerController@getReviewListByTrainerID');

    //Bookings
    Route::post('bookings/store', 'BookingController@store');
    Route::post('bookings', 'BookingController@getitems');
    Route::post('booking/delete', 'BookingController@deleteItem');
});

Route::get('config/{column}', 'API\ConfigurationController@getConfigurationByColumn');
