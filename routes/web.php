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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// User specific pages
Route::group(['prefix' => 'my', 'middleware' => ['auth']], function() {
        Route::get('events', 'EventController@my')->name('my.events');
});

Route::post('orders/track', 'OrderController@track')->name('orders.track');

Route::resources([
    'events' => 'EventController',

    'orders' => 'OrderController',

    'venues' => 'VenueController',
    'venues.stages' => 'StageController',
    'stages.sections' => 'SectionController',
    'sections.seats' => 'SeatController',
]);


Route::get('shows/api', 'ShowController@api')->name('shows.api');

Route::resource('events.shows', 'ShowController')
    ->only(['create', 'store']);

Route::resource('shows', 'ShowController')
    ->only(['index', 'show', 'edit', 'update', 'destroy']);


Route::group(['prefix' => 'reserves'], function() {
    Route::get('show/{show}/section/{section}', 'ReserveController@show')
        ->name('reserves.show');
    Route::get('show/{show}/seat/{seat}/store', 'ReserveController@store')
        ->name('reserves.store');
    Route::get('show/{show}/seat/{seat}/destroy', 'ReserveController@destroy')
        ->name('reserves.destroy');
});

Route::group(['prefix' => 'sectionshows'], function() {
    Route::post('show/{show}', 'SectionShowController@store')
        ->name('sectionshows.shows.store');
});


Route::group(['prefix' => 'seatfactory/{section}'], function() {
    Route::delete('', 'SeatFactoryController@destroy')
        ->name('seatfactory.destroy');
    Route::post('', 'SeatFactoryController@store')
        ->name('seatfactory.store');
});


Route::get('places', 'PlaceController@index')->name('places.index');


// Users
Route::resource('users', 'UserController')
    ->only(['show', 'edit', 'update']);