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


Route::get('/', 'DataTableController@index')->name('home');
Route::get('datatable', 'DataTableController@index');
Route::get('datatable/getdata', 'DataTableController@getPosts')->name('datatable/getdata');

Route::resource('post','PostsController')->middleware('auth');
Route::get('post/{id}/show-delete','PostsController@delete')->middleware('auth');

Auth::routes();

Route::get('/home', 'PostsController@index')->name('dashboard')->middleware('auth');