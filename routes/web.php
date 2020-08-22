<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();
Route::post('/saveAllianceIDs', 'AllianceController@saveAllianceIDs');
Route::post('/saveAllianceData','AllianceController@saveAllianceData');
Route::post('/saveTimers','AllianceController@saveTimers');
Route::get('/test', 'AppController@test');
//Route::get('/test2', 'AllianceController@getnewAllianceIDs');
Route::get('/getNewAllianceIDs', 'AllianceController@getnewAllianceIDs');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/{any}', 'AppController@index')->where('any', '.*');



