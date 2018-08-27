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
    Route::post('categories/{categoryID}', 'CategoryController@update')
        ->where('categoryID', '[0-9]+');
    Route::delete('categories/{categoryID}/', 'CategoryController@delete')
        ->where('categoryID', '[0-9]+');

    Route::post('items/', 'ItemController@store');
    Route::post('items/{itemID}', 'ItemController@update')
        ->where('itemID', '[0-9]+');
    Route::delete('items/{itemID}/', 'ItemController@delete')
        ->where('itemID', '[0-9]+');

    Route::post('/categories/{categoryID}/parameters/', 'CategoryParametersController@store')
        ->where('categoryID', '[0-9]+');
    Route::post('/categories/{categoryID}/parameters/{parameterID}', 'CategoryParametersController@update')
        ->where('categoryID', '[0-9]+');
    Route::delete('/categories/parameters/{categoryID}', 'CategoryParametersController@delete')
        ->where('categoryID', '[0-9]+');

    Route::post('/parameters', 'ParameterController@store');
    Route::post('/parameters/{parameterID}', 'ParameterController@update');
    Route::delete('/parameters/{parameter}', 'ParameterController@delete');

    Route::post('items/{itemID}/parameters', 'ItemParametersController@store')
        ->where('itemID', '[0-9]+');
    Route::post('items/{itemID}/parameters/{parameterID}', 'ItemParametersController@update')
        ->where('itemID', '[0-9]+');
    Route::delete('items/{itemID}/parameters/{parameterID}', 'ItemParametersController@delete')
        ->where(['itemID' => '[0-9]+', 'parameterID' => '[0-9]+']);
});

Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

Route::group(['middleware' => 'responseApi'], function () {
    Route::get('/categories/parameters/', 'CategoryParametersController@index');
    Route::get('/categories/parameters/{categoryParameterID}', 'CategoryParametersController@show')
        ->where('categoryParameterID', '[0-9]+');

    Route::get('/categories/', 'CategoryController@index');
    Route::get('/categories/{categoryID}/parameters', 'CategoryParametersController@getByCategory')
        ->where('categoryID', '[0-9]+');
    Route::get('/categories/{categoryID}/', 'CategoryController@show')
        ->where('categoryID', '[0-9]+');


    Route::get('items/', 'ItemController@index');
    Route::get('items/{itemID}', 'ItemController@show')
        ->where('itemID', '[0-9]+');

    Route::get('items/parameters', 'ItemParametersController@index');
//    Route::get('items/{itemID}/parameters', 'ItemParametersController@getByItem')
//        ->where('itemID', '[0-9]+');

    Route::get('/parameters', 'ParameterController@index');
    Route::get('/parameters/{parameterID}', 'ParameterController@show')
        ->where('parameterID', '[0-9]+');
});

