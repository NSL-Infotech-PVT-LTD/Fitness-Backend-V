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
    Route::post('myfun', 'HomeController@myfun')->name('home.revenue');
    Route::resource('tournament', 'Admin\TournamentsController');
    Route::get('mydata/{id}', 'Admin\EnrollmentsController@enrollmentByid');
    Route::resource('enrollments', 'Admin\EnrollmentsController');
    Route::get('/', 'HomeController@index')->name('home');;
    Route::resource('roles', 'Admin\RolesController');
    Route::resource('permissions', 'Admin\PermissionsController');
    Route::resource('users', 'Admin\UsersController');
    Route::resource('pages', 'Admin\PagesController');
    Route::resource('activitylogs', 'Admin\ActivityLogsController')->only([
        'index', 'show', 'destroy'
    ]);

    //do here
    
    Route::get('admin/users/index/customer', 'Admin\UsersController@routeToCustomer');
    Route::get('admin/users/index/trainer', 'Admin\UsersController@routeToTrainer');
    
    
    
    
    
    //here

    
    
    
    
    
    
    
    
    
    
    
    
    



    Route::resource('settings', 'Admin\SettingsController');
    Route::post('user/change-status', 'Admin\UsersController@changeStatus')->name('user.changeStatus');
    Route::post('tournament/change-status', 'Admin\TournamentsController@changeStatus')->name('tournament.changeStatus');
    Route::post('enrollments/winnerstatus', 'Admin\EnrollmentsController@winnerstatus')->name('enrollment.winnerstatus');

    Route::get('generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@getGenerator']);
    Route::post('generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@postGenerator']);
});

Route::resource('admin/products', 'Admin\\ProductsController');

Route::resource('admin/service', 'Admin\\ServiceController');
Route::post('admin/service/change-status', 'Admin\ServiceController@changeStatus')->name('service.changeStatus');
Route::resource('admin/class', 'Admin\\ClassController');
Route::post('admin/class/change-status', 'Admin\ClassController@changeStatus')->name('class.changeStatus');
Route::resource('admin/training-detail', 'Admin\\TrainingDetailController');
Route::post('admin/training-detail/change-status', 'Admin\TrainingDetailController@changeStatus')->name('training-detail.changeStatus');
Route::resource('admin/activity-plan', 'Admin\\ActivityPlanController');
Route::post('admin/activity-plan/change-status', 'Admin\ActivityPlanController@changeStatus')->name('activity-plan.changeStatus');
Route::resource('admin/events', 'Admin\\EventsController');
Route::post('admin/events/change-status', 'Admin\EventsController@changeStatus')->name('events.changeStatus');
Route::resource('admin/special-events', 'Admin\\SpecialEventsController');
Route::post('admin/special-events/change-status', 'Admin\SpecialEventsController@changeStatus')->name('special-events.changeStatus');

