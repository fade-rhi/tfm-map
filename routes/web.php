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

Route::get('/seed', 'CommuneController@updateCommBizAdjListInDb');

Auth::routes();
Route::get('/home', 'CommuneController@index');
Route::get('/', 'CommuneController@index');
Route::get('/backoffice', 'CommuneController@admin');
Route::post('/addcommunebiz', 'CommuneController@addBiz');
Route::post('/addcommuneopport', 'CommuneController@addOpport');
Route::get('/deleteBiz/{insee}','CommuneController@deleteBiz');
Route::get('/deleteOpport/{insee}','CommuneController@deleteOpport');