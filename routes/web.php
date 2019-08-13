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

Route::resource('post','PostsController')->middleware('auth');

Auth::routes();

Route::get('/home', 'PostsController@index')->middleware('auth');
Route::get('/', 'PostsController@index')->middleware('auth');
Route::get('datatable/getdata', 'PostsController@getPosts')->name('datatable/getdata');
