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

// Auth::routes();

// Route::get('profile', function(){

// })->middleware('auth.basic');

Route::get('/timers', 'HomeController@index');
Route::get('/getTimerData','TimerController@getTimerData');
Route::get('/updateTimerData','TimerController@updateTimerData');
Route::get('/test', 'AuthController@test');

Route::get('/oauth/login', 'AuthController@redirectToProvider');
Route::get('/oauth/callback', 'AuthController@handleProviderCallback');


Route::get('/party', 'HomeController@party');
Route::get('/party2', 'HomeController@party2');
Route::get('/updateNotifications', 'NotificationController@getNotifications');


Route::get('/home', 'HomeController@index')->name('home');
Route::get('updateAlliances', 'AllianceController@updateAlliances');
Route::get('/helper', function () {
    return Helper::displayName();
});
Route::get('/{any}', 'AppController@index')->where('any', '.*');



