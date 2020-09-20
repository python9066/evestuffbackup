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
    Route::put('/notifications/{id}', 'NotificationController@update');

    Route::get('/timers','TimerController@getTimerData');

    Route::get('/campaigns','CampaignRecordsController@index');
    Route::put('/campaigns/{id}','CampaignRecordsController@update');
    Route::get('/systemsinconstellation/{id}','SystemController@systemsinconstellation');

    Route::get('/campaignusersrecords/{id}','CampaignUserRecordsController@show');
    Route::put('/campaignusersrecords/{id}/{campid}', 'CampaignUserRecordsController@update');
    Route::post('/campaignusersrecords/{id}/{campid}', 'CampaignUserRecordsController@store');

    Route::post('/campaignusers/{campid}','CampaignUserController@store');
    Route::put('/campaignusers/{id}/{campid}','CampaignUserController@update');
    Route::delete('/campaignusers/{id}/{campid}','CampaignUserController@destroy');

    Route::get('/campaignsystemsrecords','CampaignSystemRecordsController@index');
    Route::get('/campaignsystemsrecords/{id}','CampaignSystemRecordsController@show');
    Route::put('/campaignsystemsrecords/{id}/{campid}','CampaignSystemRecordsController@update');
    Route::post('/campaignsystemsrecords/{id}/{campid}','CampaignSystemRecordsController@store');
    Route::delete('/campaginsystemsrecords/{id}/{campid}','CampaignSystemRecordsController@destroy');

    Route::post('/campaignsystems/{campid}', 'CampaignSystemsController@store');
    Route::put('/campaignsystems/{id}/{campid}', 'CampaignSystemsController@update');
    Route::delete('/campaignsystems/{id}/{campid}','CampaignSystemsController@destroy');



});
