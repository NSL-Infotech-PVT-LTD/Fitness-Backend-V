<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');





Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'roles'], 'roles' => ['Super-Admin']], function () {
     Route::get('users/role/{role_id}', 'Admin\UsersController@indexByRoleId')->name('users-role');
    Route::get('home', 'HomeController@index')->name('home');
    Route::resource('tournament', 'Admin\TournamentsController');
    Route::get('mydata/{id}', 'Admin\EnrollmentsController@enrollmentByid');
    Route::get('/', 'Admin\AdminController@index');
    Route::resource('roles', 'Admin\RolesController');
    Route::resource('permissions', 'Admin\PermissionsController');
    Route::resource('users', 'Admin\UsersController');
    Route::resource('pages', 'Admin\PagesController');
    Route::resource('activitylogs', 'Admin\ActivityLogsController')->only([
        'index', 'show', 'destroy'
    ]);
    Route::resource('settings', 'Admin\SettingsController');
Route::post('user/change-status', 'Admin\UsersController@changeStatus')->name('user.changeStatus');
Route::post('tournament/change-status', 'Admin\TournamentsController@changeStatus')->name('tournament.changeStatus');
Route::post('enrollments/winnerstatus', 'Admin\EnrollmentsController@winnerstatus')->name('enrollment.winnerstatus');

    Route::get('generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@getGenerator']);
    Route::post('generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@postGenerator']);
});

Route::resource('admin/products', 'Admin\\ProductsController');
