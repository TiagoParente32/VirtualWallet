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
    Route::get('users/me/wallet', 'WalletController@getWallet');
    //Route::get('users/me/movements', 'MovementController@getWalletMovements');

    //CATEGORIES
    Route::get('categories','CategoryController@index');

    //Criar grupo para os dois tipos de utilizadores ou criar duas rotas a apontar para o mesmo metodo
    Route::post('movement/create', 'MovementController@store');

    //group of routes to users
    Route::group(['middleware' => 'type:u'], function () {
        Route::get('users/me/wallet', 'UserController@getWallet');
        Route::get('users/me/wallet/movements', 'UserController@getMovements');
        //Route::post('movement/create', 'MovementController@store');
        Route::put('movements/{movement}','MovementController@update');
        Route::get('users/me/wallet/movements/filter', 'MovementController@filterWalletMovements');
    });
    //group of routes to operators
    Route::group(['middleware' => 'type:o'], function () {
        //Route::post('movement/create', 'MovementController@store');
    });
    //group of routes to admins
    Route::group(['middleware' => 'type:a'], function () {
        Route::post('users/operator','UserController@storeOperator');
        Route::post('users/administrator','UserController@storeAdministrator');
        Route::get('users','UserController@index');
        Route::delete('users/{id}','UserController@destroy');
        Route::patch('users/{id}','UserController@DeactivateReactivateUser');
        Route::get('users/filter','UserController@filterUsers');
    });

});
