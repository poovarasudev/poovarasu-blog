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
    Route::post('login', 'Api\v1\AuthController@login')->name('Login Route');
    Route::post('logout', 'Api\v1\AuthController@logout')->name('Log Out');
    Route::post('refresh', 'Api\v1\AuthController@refresh')->name('Refresh Route');
    Route::post('me', 'Api\v1\AuthController@me');
    Route::get('posts', 'Api\v1\AuthController@getPosts');
});

Route::group(['prefix' => 'v1'], function () {
    Route::get('/api-post', 'Api\v1\ApiPostCrudController@index')->name('All Post');
    Route::post('/api-post/create', 'Api\v1\ApiPostCrudController@store')->name('Create Post');
    Route::get('/api-post/{id}', 'Api\v1\ApiPostCrudController@show')->name('Show Post');
    Route::put('/api-post/{id}/update', 'Api\v1\ApiPostCrudController@update')->name('Update Post');
    Route::delete('/api-post/{id}/delete', 'Api\v1\ApiPostCrudController@destroy')->name('Delete Post');
});
