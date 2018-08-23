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
    Route::post('categories/', 'CategoryController@store');
    Route::post('categories/{category}', 'CategoryController@update');
    Route::delete('categories/{category}/', 'CategoryController@delete');

    Route::post('items/', 'ItemController@store');
    Route::post('items/{id}', 'ItemController@update');
    Route::delete('items/{id}/', 'ItemController@delete');

    Route::post('/categories/{categoryID}/parameters/', 'CategoryParametersController@store');
//    Route::post('/categories/{categoryID}/parameters/{parameterID}', 'CategoryParametersController@update');
    Route::delete('/categories/parameters/{id}', 'CategoryParametersController@delete');
});

Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

Route::group(['middleware' => 'responseApi'], function () {
    Route::get('/categories/parameters/', 'CategoryParametersController@index');
    Route::get('/categories/parameters/{id}', 'CategoryParametersController@show');

    Route::get('/categories/', 'CategoryController@index');
    Route::get('/categories/{id}/parameters', 'CategoryParametersController@getByCategory');
    Route::get('/categories/{id}/', 'CategoryController@show');


    Route::get('/items/', 'ItemController@index');
    Route::get('/items/{id}', 'ItemController@show');
});

