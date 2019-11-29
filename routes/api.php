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


//routes to unauthenticated users
Route::get('walletcount', 'WalletController@getwalletcount');
Route::post('login', 'LoginControllerAPI@login');

Route::post('register', 'UserController@store');

//group of routes to authenticated users
Route::group(['middleware' => ['auth:api']], function () {

    //common routes between all types of users

    Route::post('logout', 'LoginControllerAPI@logout');
    Route::put('users/me/edit', 'UserController@update');
    Route::put('users/me', 'UserController@update');
    Route::get('users/me', 'UserController@getMe');

    //group of routes to users
    Route::group(['middleware' => 'type:u'], function () { });
    //group of routes to operators
    Route::group(['middleware' => 'type:o'], function () { });
    //group of routes to admins
    Route::group(['middleware' => 'type:a'], function () { });
});
