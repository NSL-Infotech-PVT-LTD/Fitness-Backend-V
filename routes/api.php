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

Route::post('customer/login', 'API\AuthController@login');
Route::post('service-provider/login', 'API\AuthController@login');
Route::post('customer/register', 'API\AuthController@register');
Route::post('service_provider/register', 'API\AuthController@service_provider');



Route::group(['middleware' => ['auth:api', 'roles'], 'namespace' => 'API'], function() {
//    Route::group(['middleware' => ['auth:api', 'roles'], 'roles' => ['App-Users'],'namespace' => 'API'], function() {
//    Route::post('getDirectories', 'AuthController@getDirectories');
});

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('update', 'API\AuthController@Update');
    Route::post('tournaments/list', 'API\AuthController@getitems');
    Route::post('tournament/detail', 'API\AuthController@getitem');
    Route::post('enroll', 'API\AuthController@enroll');
    Route::post('enrollments/list', 'API\AuthController@getMyenroll');
    Route::post('all/enrollments', 'API\AuthController@getAllenrollUsers');
    Route::post('getTournamentDetails', 'API\AuthController@getTournamentDetails');
});


Route::post('reset-password', 'API\AuthController@resetPassword');
