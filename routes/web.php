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
Route::get('/admin/stage/detail', 'AdminController@stageDetail');
Route::get('/admin/monster', 'AdminController@monster');
Route::get('/admin/monster/detail', 'AdminController@monsterDetail');
Route::get('/admin/monster/clue', 'AdminController@monsterClue');
Route::get('/admin/log', 'AdminController@log');

Route::post('/admin/add/stage', 'AdminController@addStage');
Route::post('/admin/add/stage/detail', 'AdminController@addStageDetail');
Route::post('/admin/add/monster', 'AdminController@addMonster');
Route::post('/admin/add/monster/detail', 'AdminController@addMonsterDetail');
Route::post('/admin/add/monster/clue', 'AdminController@addMonsterClue');

Route::post('/user/login', 'UsersController@login');

Route::post('/line/callback', 'LineController@callback');
