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

Route::any('/update_by_hook', 'PaymentController@updateByHook');
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

Route::group(['prefix' => 'trainer', 'namespace' => 'API'], function () {
    Route::post('login', 'AuthTrainerController@Login');
    Route::post('reset-password', 'AuthTrainerController@resetPassword');
    Route::get('logout', 'AuthTrainerController@logout');
});
Route::group(['prefix' => 'trainer', 'middleware' => 'auth:trainer-api', 'namespace' => 'API'], function () {
    Route::post('bookings', 'AuthTrainerBookingController@getitems');
    Route::post('get-dates', 'AuthTrainerBookingController@getScheduledDates');
    Route::post('get-by-id', 'AuthTrainerBookingController@getitem');
    Route::post('add-date', 'AuthTrainerBookingController@addScheduleDate');
});

Route::group(['middleware' => 'auth:api', 'namespace' => 'API'], function() {
    Route::post('update', 'AuthController@Update');
    Route::post('update/role', 'AuthController@upgradePlan');
    Route::post('get-profile', 'AuthController@getProfile');
    Route::post('events', 'EventController@getItems');
    Route::post('event', 'EventController@getItem');

    Route::post('class-schedules', 'ClassScheduleController@getItems');
    Route::post('class-schedule', 'ClassScheduleController@getItem');

    //Bookings
    Route::post('bookings/store', 'BookingController@store');
    Route::post('bookings', 'BookingController@getitems');
    Route::post('booking/delete', 'BookingController@deleteItem');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'register', 'namespace' => 'API'], function() {
    Route::post('trainers', 'TrainerController@getitems');
    Route::post('trainer', 'TrainerController@getitem');
    Route::post('trainer/reviews', 'TrainerController@getReviewListByTrainerID');
});
Route::group(['namespace' => 'API'], function() {
    Route::post('trainers', 'TrainerController@getitems');
    Route::post('trainer', 'TrainerController@getitem');
    Route::post('trainer/reviews', 'TrainerController@getReviewListByTrainerID');
});
Route::group(['middleware' => ['auth:api', 'roles'], 'namespace' => 'API'], function() {
    Route::post('notification/list', 'NotificationController@notifications');
    Route::post('notification/read', 'NotificationController@notificationRead');
//    Route::post('notification/status', 'AuthController@updateNotifyStatus');
});
Route::get('config/{column}', 'API\ConfigurationController@getConfigurationByColumn');
