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

Route::get('/', function () {
    return view('stage');
});

Route::get('/admin', 'AdminController@main');
Route::get('/admin/stage', 'AdminController@stage');
Route::get('/admin/monster', 'AdminController@monster');

Route::post('/admin/add/stage', 'AdminController@addStage');
Route::post('/admin/add/monster', 'AdminController@addMonster');