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

Route::group(['middleware' => 'auth:api', 'namespace' => 'API'], function() {
    Route::post('update', 'AuthController@Update');
    Route::post('events', 'EventController@getItems');
});
