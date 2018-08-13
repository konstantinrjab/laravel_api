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
    Auth::guard('api')->user(); // instance of the logged user
    Auth::guard('api')->check(); // if a user is authenticated
    Auth::guard('api')->id(); // the id of the authenticated user
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('categories', 'CategoryController@store');
    Route::put('categories/{category}', 'CategoryController@update');
    Route::delete('categories/{category}', 'CategoryController@delete');
});

Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');


Route::group(['middleware' => 'responseApi'], function () {
    Route::get('/categories', 'CategoryController@index');
    Route::get('/categories/{category}', 'CategoryController@show');
    
    Route::get('/items', 'ItemController@index');
    Route::get('/items/{item}', 'ItemController@show');
});
