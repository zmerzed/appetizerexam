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

Route::get('/', 'HomeController@index');

//Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/init', 'ExamineeController@download');

Route::get('/examinees', 'ExamineeController@index');
Route::post('/examinees', 'ExamineeController@store');

Route::get('/schools', 'ExamineeController@schools');