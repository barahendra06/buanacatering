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
// URL::forceScheme('https');
// find user by email
Route::post('user/find-by-email', 'Api\UserController@findUserByEmail')->name('user-find-by-email');

// check for authorized user through token
//Route::group(['middleware' => 'auth:api'], function () {
//
//	Route::group(['prefix' => 'user'], function () {
//
//		// get user
//    	Route::get('', 'Api\UserController@getUser')->name('user-get');
//	});
//
//});

// Home
Route::get('home', 'Api\HomeController@index')->name('home-index');

Route::group(['prefix' => 'auth'], function () {

    // check for authorized site to access
    Route::group(['middleware' => 'api.whitelist'], function () {
        // login 
        Route::post('login', 'Api\AuthController@postLogin')->name('auth-login');
  	});
});
