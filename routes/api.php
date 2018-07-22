<?php

use Illuminate\Http\Request;
Use App\Article;
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

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('articles', 'ArticleController@store');
    Route::put('articles/{article}', 'ArticleController@update');
    Route::delete('articles/{article}', 'ArticleController@delete');
});

Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

Route::get('articles', 'ArticleController@index');
Route::get('articles/{article}', 'ArticleController@show');
