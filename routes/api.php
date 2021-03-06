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
    return $request->user();
});

//Route::resource('posts', 'PostController');

Route::get('posts', 'PostController@index');

Route::get('post/{id}', 'PostController@show');

Route::post('post', 'PostController@store');

Route::patch('post', 'PostController@store');

Route::delete('post/{id}', 'PostController@destroy');
