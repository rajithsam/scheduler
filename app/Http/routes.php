<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users/{user}', [
    'uses' => 'UsersController@show',
    'as' => 'users.show'
]);

Route::get('/users/{user}/shifts', [
    'uses' => 'UsersController@listShifts',
    'as' => 'users.shifts.list'
]);

Route::get('/users/{user}/hours', [
    'uses' => 'UsersController@listHours',
    'as' => 'users.hours.list'
]);

Route::get('/users/{user}/coworkers', [
    'uses' => 'UsersController@listCoworkers',
    'as' => 'users.coworkers.list'
]);