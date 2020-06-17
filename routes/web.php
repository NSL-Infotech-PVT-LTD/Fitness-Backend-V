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





Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin']], function () {



    Route::get('users/role/{role_id}', 'Admin\UsersController@indexByRoleId')->name('users-role');
    Route::get('users/create/{role_id}', 'Admin\UsersController@createWithRole')->name('user.create.withrole');


    Route::get('home', 'HomeController@index')->name('home');
    Route::post('myfun', 'HomeController@myfun')->name('home.revenue');
    Route::resource('tournament', 'Admin\TournamentsController');
    Route::get('mydata/{id}', 'Admin\EnrollmentsController@enrollmentByid');
    Route::resource('enrollments', 'Admin\EnrollmentsController');
    Route::get('/', 'HomeController@index')->name('home');
    ;
    Route::resource('roles', 'Admin\RolesController');
    Route::resource('permissions', 'Admin\PermissionsController');
    Route::resource('users', 'Admin\UsersController');
    Route::resource('adminusers', 'Admin\AdminUsersController');
    Route::resource('pages', 'Admin\PagesController');
    Route::resource('activitylogs', 'Admin\ActivityLogsController')->only([
        'index', 'show', 'destroy'
    ]);
    //do here
    Route::get('users/index/customer', 'Admin\UsersController@routeToCustomer');
    Route::get('users/index/trainer', 'Admin\UsersController@routeToTrainer');
    //here
    Route::resource('settings', 'Admin\SettingsController');
    Route::post('user/change-status', 'Admin\UsersController@changeStatus')->name('user.changeStatus');
    Route::post('user/send-payment-link', 'Admin\UsersController@sendPayment')->name('user.sendPayment');
    Route::post('role/change-status', 'Admin\RolesController@changeStatus')->name('role.changeStatus');
    Route::resource('trainer-user', 'Admin\\TrainerUserController');
    Route::post('trainer-user/change-status', 'Admin\TrainerUserController@changeStatus')->name('trainer-user.changeStatus');


    Route::resource('service', 'Admin\\ServiceController');
    Route::post('service/change-status', 'Admin\ServiceController@changeStatus')->name('service.changeStatus');
    Route::resource('class', 'Admin\\ClassController');
    Route::post('class/change-status', 'Admin\ClassController@changeStatus')->name('class.changeStatus');
    Route::resource('class-schedule', 'Admin\\ClassScheduleController');
    Route::post('class-schedule/change-status', 'Admin\ClassScheduleController@changeStatus')->name('class-schedule.changeStatus');
    Route::resource('events', 'Admin\\EventsController');
    Route::post('event/change-status', 'Admin\EventsController@changeStatus')->name('events.changeStatus');
    Route::post('event/special', 'Admin\EventsController@MarkSpecial')->name('event.markspecial');
    Route::resource('event-location', 'Admin\\EventLocationController');
    Route::post('event-location/change-status', 'Admin\EventLocationController@changeStatus')->name('event-location.changeStatus');





    Route::post('membership/change-status', 'Admin\MembershipController@changeStatus')->name('membership.changeStatus');
    Route::resource('membership', 'Admin\\MembershipController');
    Route::resource('activity-plan', 'Admin\\ActivityPlanController');
    Route::post('activity-plan/change-status', 'Admin\ActivityPlanController@changeStatus')->name('activity-plan.changeStatus');
    Route::get('generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@getGenerator']);
    Route::post('generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@postGenerator']);
});
