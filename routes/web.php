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
use utils\Helper\Helper;

Auth::routes();
Route::post('/saveAllianceIDs', 'AllianceController@saveAllianceIDs');
Route::post('/saveAllianceData','AllianceController@saveAllianceData');
Route::post('/saveTimers','AllianceController@saveTimers');

Route::get('/getTimerData','TimerController@getTimerData');
Route::get('/timers', 'HomeController@index');




Route::get('/getNewAllianceIDs', 'AllianceController@getnewAllianceIDs');
Route::get('/party', 'HomeController@party');
Route::get('/party2', 'HomeController@party2');
Route::get('/updateNotifications', 'NotificationController@getNotifications');

Route::get('/getAllianceStanding', 'AllianceController@getAllianceStanding');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/test', 'AllianceController@test');
Route::get('/helper', function () {
    return Helper::displayName();
});
Route::get('/{any}', 'AppController@index')->where('any', '.*');



