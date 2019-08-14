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


Route::group(['middleware' => ['auth']], function () {
    Route::resource('post','PostsController');
    Route::get('datatable/getdata', 'PostsController@getPosts')->name('datatable/getdata');
});

Route::group(['middleware' => ['permission:create comment|edit comment|delete comment|view comment']], function () {
    Route::resource('comment','CommentsController');
});

Auth::routes();
Route::get('/no-role-page', 'HomeController@noRolePage');
Route::get('/', 'HomeController@index')->middleware('auth');

Route::group(['middleware' => ['role:admin']], function () {
    Route::resource('user','UsersController');
    Route::resource('role','RolesController');
    Route::get('datatable/getrole', 'RolesController@getRoles')->name('datatable/getrole');
    Route::get('datatable/getuser', 'UsersController@getUsers')->name('datatable/getuser');

    Route::get('/dashboard', 'HomeController@dashboard');
});


Route::get('get-dashboard-datas', 'HomeController@getDashboardDatas');
