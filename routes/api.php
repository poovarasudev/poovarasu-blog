<?php


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
    Route::post('login', 'Api\v1\AuthController@login')->name('login_route');
    Route::post('logout', 'Api\v1\AuthController@logout')->name('log_out')->middleware('JWTAuthentication');
    Route::post('refresh', 'Api\v1\AuthController@refresh')->name('refresh_route')->middleware('JWTAuthentication');
    Route::post('me', 'Api\v1\AuthController@me')->middleware('JWTAuthentication');
});

Route::group(['prefix' => 'v1'], function () {

    Route::group(['middleware' => 'jwt_auth'], function () {
        Route::post('/post/create', 'Api\v1\PostController@store')->name('create_post');
        Route::delete('/post/{id}/delete', 'Api\v1\PostController@destroy')->name('delete_post');
    });

    Route::group([], function () {
        Route::get('/post', 'Api\v1\PostController@index')->name('all_post');
        Route::get('/post/{id}', 'Api\v1\PostController@show')->name('show_post');
        Route::put('/post/{id}/update', 'Api\v1\PostController@update')->name('update_post');
    });
});
