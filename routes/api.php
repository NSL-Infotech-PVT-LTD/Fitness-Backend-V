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

Route::post('forget-password', 'API\AuthController@resetPassword');

Route::group(['middleware' => ['auth:api', 'roles'], 'namespace' => 'API'], function() {
//    Route::group(['middleware' => ['auth:api', 'roles'], 'roles' => ['App-Users'],'namespace' => 'API'], function() {
//    Route::post('getDirectories', 'AuthController@getDirectories');
    
});

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('customer/change-password', 'API\AuthController@changePassword');
    Route::post('service-provider/change-password', 'API\AuthController@changePassword');
    Route::get('configuration/{column}', 'API\ConfigurationController@getConfigurationColumn');
});
