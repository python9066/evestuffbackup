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

Route::middleware('auth:api')->group(function () {
    Route::post('/brois', 'testController@notifications');
    Route::get('/notifications/{region_id}', 'NotificationRecordsController@regionLink');
    Route::get('/notifications', 'NotificationRecordsController@index');
    Route::put('/notifications/{id}', 'NotificationController@update');
    Route::put('/notificationsaddtime/{id}', 'NotificationController@addTime');

    Route::get('/timers', 'TimerController@getTimerData');

    Route::get('/campaigns', 'CampaignRecordsController@index');
    Route::get('/campaignslist', 'CampaignRecordsController@campaignslist');
    Route::put('/campaigns/{id}', 'CampaignRecordsController@update');
    Route::get('/systemsinconstellation/{id}', 'SystemController@systemsinconstellation');

    Route::get('/campaignusersrecords/{id}', 'CampaignUserRecordsController@show');
    Route::get('/campaignusersrecordsbychar/{id}', 'CampaignUserRecordsController@bychar');
    Route::put('/campaignusersrecords/{id}/{campid}', 'CampaignUserRecordsController@update');
    Route::post('/campaignusersrecords/{id}/{campid}', 'CampaignUserRecordsController@store');


    Route::post('/campaignusers/{campid}', 'CampaignUserController@store');
    Route::put('/campaignusers/{id}/{campid}', 'CampaignUserController@update');
    Route::put('/campaignusersadd/{id}/{campid}', 'CampaignUserController@updateadd');
    Route::put('/campaignusersremove/{id}/{campid}', 'CampaignUserController@updateremove');
    Route::delete('/campaignusers/{id}/{campid}/{siteid}', 'CampaignUserController@destroy');

    Route::get('/campaignsystemsrecords', 'CampaignSystemRecordsController@index');
    Route::get('/campaignsystemsrecords/{id}', 'CampaignSystemRecordsController@show');
    Route::put('/campaignsystemsrecords/{id}/{campid}', 'CampaignSystemRecordsController@update');
    Route::post('/campaignsystemsrecords/{id}/{campid}', 'CampaignSystemRecordsController@store');
    Route::delete('/campaginsystemsrecords/{id}/{campid}', 'CampaignSystemRecordsController@destroy');

    Route::post('/campaignsystemload', 'CampaignSystemsController@load');
    Route::post('/afterextranodeload', 'CampaignSystemsController@afterExtraNodeLoad');
    Route::post('/campaignsystems/{campid}', 'CampaignSystemsController@store');
    Route::put('/campaignsystems/{id}/{campid}', 'CampaignSystemsController@update');
    Route::put('/campaignsystemsnodemessage/{id}', 'CampaignSystemsController@updateMessage');
    Route::put('/campaignsystemsattackmessage/{id}', 'CampaignSystemsController@updateAttackMessage');
    Route::put('/campaignsystemsupdatetime/{id}/{campid}', 'CampaignSystemsController@updatetime');
    Route::delete('/campaignsystems/{id}/{campid}', 'CampaignSystemsController@destroy');
    Route::put('/campaignsystemremovechar/{campid}', 'CampaignSystemsController@removechar');
    Route::put('/campaignsystemmovechar/{campid}', 'CampaignSystemsController@movechar');
    Route::get('/campaignsystemcheckaddchar/{campid}', 'CampaignSystemsController@checkAddChar');
    Route::post('/campaignsystemuserskick/{campid}', 'CampaignSystemsController@kickUser');
    Route::get('/campaignsystemfinished/{campid}', 'CampaignSystemsController@finishCampaign');
    Route::get('/mcampaignsystemfinished/{campid}', 'CampaignSystemsController@mfinishCampaign');
    Route::put('campaignsystemstidi/{sysid}/{campid}', 'CampaignSystemsController@tidi');
    Route::put('campaignsystemstidimulti/{sysid}/{campid}', 'CampaignSystemsController@tidimulti');

    Route::get('/users', 'AuthController@index');
    Route::get('/userrolerecord', 'UserRolesRecordsController@index');

    Route::get('/roles', 'RoleController@index');
    Route::get('/allusersroles', 'RoleController@getAllUsersRoles');
    Route::put('/rolesadd', 'RoleController@addRole');
    Route::put('/rolesremove', 'RoleController@removeRole');

    Route::post('/campaignsystemusers/{campid}', 'CampaignSystemUsersController@store');
    Route::get('/campaignsystemusers/{campid}', 'CampaignSystemUsersController@index');
    Route::delete('/campaignsystemusers/{id}/{campid}', 'CampaignSystemUsersController@destroy');

    Route::get('/stationrecords', 'StationRecordsController@index');
    Route::put('/stationrecords/{id}', 'StationRecordsController@update');

    Route::get('/towersrecords', 'TowerRecordsController@index');
    Route::put('/towerrecords/{id}', 'TowerRecordsController@update');

    Route::post('/feedback', 'FeedBackController@store');
    Route::get('/feedback', 'FeedBackController@index');
    Route::delete('/feedback/{id}', 'FeedBackController@destroy');

    Route::post('/multicampaigns/{campid}/{name}', 'CustomCampaignsController@store');
    Route::get('/multicampaigns', 'CustomCampaignsController@index');
    Route::delete('/multicampaigns/{id}', 'CustomCampaignsController@destroy');
    Route::post('/multicampaignsedit/{campid}/{name}', 'CustomCampaignsController@edit');

    Route::get('/campaignjoin', 'CampaignJoinsController@index');
    Route::get('/campaignjoinbyid/{campid}', 'CampaignJoinsController@indexByID');
    Route::get('/campaignjoin/{id}', 'CampaignJoinsController@show');
    Route::get('/campaignjoinlist/{id}', 'CampaignJoinsController@list');
    Route::get('/campaignjoinsystems/{id}', 'CampaignJoinsController@campaignSystems');

    Route::get('/campaignsolasystems', 'CampaignSolaSystemsController@index');
    Route::put('/campaignsolasystems/{solaid}/{campid}', 'CampaignSolaSystemsController@update');

    Route::post('/checkaddnode/{campid}', 'LoggingController@NodeAdd');
    Route::post('/checkdeletenode/{campid}', 'LoggingController@NodeDelete');
    Route::get('/checkjoinleavecampaign/{campid}/{charid}/{logtype}', 'LoggingController@joinleaveCampaign');
    Route::get('/mcheckjoinleavecampaign/{campid}/{charid}/{logtype}', 'LoggingController@mjoinleaveCampaign');
    Route::put('/checklastedchecked/{campid}', 'LoggingController@lastchecked');
    Route::put('/checkscout/{campid}', 'LoggingController@systemscout');
    Route::put('/checkaddremovechar/{campid}', 'LoggingController@addremovechar');
    Route::put('/checkroleaddremove', 'LoggingController@addRemoveRoles');
    Route::get('/checkcampaign/{campid}', 'LoggingController@logCampaign');
    Route::get('/checkadmin', 'LoggingController@logAdmin');

    Route::get('/nodejoin/{campid}', 'NodeJoinsController@tableindex');
    Route::post('/nodejoin/{campid}', 'NodeJoinsController@store');
    Route::put('/nodejoinupdate/{id}/{campid}', 'NodeJoinsController@update');
    Route::put('/removecharfromnode/{id}/{campid}', 'NodeJoinsController@removeCharForNode');
    Route::put('/addchartonodeadmin/{id}/{campid}', 'NodeJoinsController@addCharToNodeAdmin');
    Route::put('/deleteextranode/{id}/{campid}', 'NodeJoinsController@deleteExtraNode');

    Route::get('/loadstationdata', 'StationController@loadStationData');
    Route::post('/taskrequest', 'StationController@taskRequest');
    Route::put('/updatestationnotification/{id}', 'StationController@update');

    Route::get('/systemlist', 'SystemController@index');
    Route::get('/ticklist', 'CorpController@index');
    Route::put('/stationname', 'StationController@reconPullbyname');
});
