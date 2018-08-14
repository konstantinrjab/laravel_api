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

Route::get('categories', 'CategoryWebController@index');
Route::get('categories/{category}', 'CategoryWebController@show');

Route::get('items', 'ItemWebController@index');
Route::post('items', 'ItemWebController@index');
Route::get('items/{item}', 'ItemWebController@show');
