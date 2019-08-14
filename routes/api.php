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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'v1/auth'], function () {
    Route::post('login', 'AuthController@login')->name('Login Route');
    Route::post('logout', 'AuthController@logout')->name('Log Out');
    Route::post('refresh', 'AuthController@refresh')->name('Refresh Route');
    Route::post('me', 'AuthController@me');
    Route::get('posts', 'AuthController@getPosts');
});

Route::group(['prefix' => 'v1'], function () {
    Route::get('/api-post', 'ApiController@index')->name('All Post');
    Route::post('/api-post/create', 'ApiController@store')->name('Create Post');
    Route::get('/api-post/{id}', 'ApiController@show')->name('Show Post');
    Route::put('/api-post/{id}/update', 'ApiController@update')->name('Update Post');
    Route::delete('/api-post/{id}/delete', 'ApiController@destroy')->name('Delete Post');
});
