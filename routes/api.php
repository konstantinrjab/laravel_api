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
    Route::post('categories/{categoryID}', 'CategoryController@update');
    Route::delete('categories/{categoryID}/', 'CategoryController@delete');

    Route::post('items/', 'ItemController@store');
    Route::post('items/{itemID}', 'ItemController@update');
    Route::delete('items/{itemID}/', 'ItemController@delete');

    Route::post('/categories/{categoryID}/parameters/', 'CategoryParametersController@store');
    Route::post('/categories/{categoryID}/parameters/{parameterID}', 'CategoryParametersController@update');
    Route::delete('/categories/parameters/{id}', 'CategoryParametersController@delete');

    Route::post('/parameters', 'ParameterController@store');
    Route::post('/parameters/{parameterID}', 'ParameterController@update');
    Route::delete('/parameters/{parameter}', 'ParameterController@delete');
});

Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

Route::group(['middleware' => 'responseApi'], function () {
    Route::get('/categories/parameters/', 'CategoryParametersController@index');
    Route::get('/categories/parameters/{categoryParameterID}', 'CategoryParametersController@show');

    Route::get('/categories/', 'CategoryController@index');
    Route::get('/categories/{categoryID}/parameters', 'CategoryParametersController@getByCategory');
    Route::get('/categories/{categoryID}/', 'CategoryController@show');


    Route::get('/items/', 'ItemController@index');
    Route::get('/items/{itemID}', 'ItemController@show');

    Route::get('/parameters', 'ParameterController@index');
    Route::get('/parameters/{parameterID}', 'ParameterController@show');
});

