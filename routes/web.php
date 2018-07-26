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

Route::get('articles', 'ArticleController@index');
Route::get('articles/{article}', 'ArticleController@show');

Route::get('categories', 'CategoryWebController@index');
Route::get('categories/{category}', 'CategoryWebController@show');

Route::get('items', 'ItemController@index');
Route::get('items/{item}', 'ItemController@show');
