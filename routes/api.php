<?php

use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/timers','TimerController@getTimerData');

// Route::middleware('auth:api')->get('/notifications', function (Request $request) {
//     return $request->notifications();
// });
// Route::get('/notifications','NotificationRecordsController@index');

Route::middleware('auth:api')->group(function(){
    Route::get('/notifications/{region_id}', 'NotificationRecordsController@regionLink');
    Route::get('/notifications','NotificationRecordsController@index');
    Route::put('/notifications/{id}', 'NotificationRecordsController@update');

    Route::get('/timers','TimerController@getTimerData');

    Route::get('/campaigns','CampaignRecordsController@index');
    Route::put('/campaigns/{id}','CampaignRecordsController@update');
    Route::get('/constellation/{id}','SystemController@contellation');



});
