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
|ffff
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/timers','TimerController@getTimerData');

// Route::middleware('ffffauth:api')->get('/notifications', function (Request $request) {
//     return $request->notifications();
// });
// Route::get('/notifications','NotificationRecordsController@index');

Route::middleware('auth:sanctum')->group(function () {

    //BROISES FEED//
    Route::post('/webway', 'WebWayController@getWebWay');
    Route::post('/brois', 'testController@notifications');
    Route::get('/test', 'testController@key');
    Route::post('/url', 'AppController@url');
    Route::get('/eveusercount', 'EveController@playerCount');

    Route::post('/rcInput', 'RCSheet@RCInput');
    Route::get('/reconpullregion', 'StationController@reconRegionPull');

    //HACKING NOTIFICATION APIS//
    Route::get('/notifications/{region_id}', 'NotificationRecordsController@regionLink');
    Route::get('/notifications', 'NotificationRecordsController@index');
    Route::put('/notifications/{id}', 'NotificationController@update');
    Route::put('/notificationsaddtime/{id}', 'NotificationController@addTime');

    //IHUB-TCU WINDOWS//
    Route::get('/timers', 'TimerController@getTimerData');
    Route::get('/timersregions', 'TimerController@getTimerDataRegions');

    //HACKING Campaign APIs
    Route::get('/campaigns', 'CampaignRecordsController@index');
    Route::get('/campaignsregion', 'CampaignRecordsController@campaignslistRegion');
    Route::get('/campaignslist', 'CampaignRecordsController@campaignslist');
    Route::put('/campaigns/{id}', 'CampaignRecordsController@update');

    //CAMPIGN USERS
    Route::get('/campaignusersrecords/{id}', 'CampaignUserRecordsController@show');
    Route::get('/campaignusersrecordsbychar/{id}', 'CampaignUserRecordsController@bychar');
    Route::put('/campaignusersrecords/{id}/{campid}', 'CampaignUserRecordsController@update');
    Route::post('/campaignusersrecords/{id}/{campid}', 'CampaignUserRecordsController@store');

    Route::post('/campaignusers/{campid}', 'CampaignUserController@store');
    Route::put('/campaignusers/{id}/{campid}', 'CampaignUserController@update');
    Route::put('/campaignusersadd/{id}/{campid}', 'CampaignUserController@updateadd');
    Route::put('/campaignusersremove/{id}/{campid}', 'CampaignUserController@updateremove');
    Route::delete('/campaignusers/{id}/{campid}/{siteid}', 'CampaignUserController@destroy');


    //Campagin NODES
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

    Route::post('/multicampaigns/{campid}/{name}', 'CustomCampaignsController@store');
    Route::get('/multicampaigns', 'CustomCampaignsController@index');
    Route::delete('/multicampaigns/{id}', 'CustomCampaignsController@destroy');
    Route::post('/multicampaignsedit/{campid}/{name}', 'CustomCampaignsController@edit');

    //SYSTEM API
    Route::get('/systemsinconstellation/{id}', 'SystemController@systemsinconstellation');
    Route::get('/systemlist', 'SystemController@index');

    Route::get('/users', 'AuthController@index');
    Route::get('/userrolerecord', 'UserRolesRecordsController@index');

    Route::get('/roles', 'RoleController@index');
    Route::get('/allusersroles', 'RoleController@getAllUsersRoles');
    Route::put('/rolesadd', 'RoleController@addRole');


    Route::put('/rolesremove', 'RoleController@removeRole');

    Route::post('/campaignsystemusers/{campid}', 'CampaignSystemUsersController@store');
    Route::get('/campaignsystemusers/{campid}', 'CampaignSystemUsersController@index');
    Route::delete('/campaignsystemusers/{id}/{campid}', 'CampaignSystemUsersController@destroy');

    Route::get('/campaignjoin', 'CampaignJoinsController@index');
    Route::get('/campaignjoinbyid/{campid}', 'CampaignJoinsController@indexByID');
    Route::get('/campaignjoin/{id}', 'CampaignJoinsController@show');
    Route::get('/campaignjoinlist/{id}', 'CampaignJoinsController@list');
    Route::get('/campaignjoinsystems/{id}', 'CampaignJoinsController@campaignSystems');

    Route::get('/campaignsolasystems', 'CampaignSolaSystemsController@index');
    Route::put('/campaignsolasystems/{solaid}/{campid}', 'CampaignSolaSystemsController@update');

    Route::get('/nodejoin/{campid}', 'NodeJoinsController@tableindex');
    Route::post('/nodejoin/{campid}', 'NodeJoinsController@store');
    Route::put('/nodejoinupdate/{id}/{campid}', 'NodeJoinsController@update');
    Route::put('/removecharfromnode/{id}/{campid}', 'NodeJoinsController@removeCharForNode');
    Route::put('/addchartonodeadmin/{id}/{campid}', 'NodeJoinsController@addCharToNodeAdmin');
    Route::put('/deleteextranode/{id}/{campid}', 'NodeJoinsController@deleteExtraNode');

    Route::get('/stationrecords', 'StationRecordsController@index');
    Route::get('/stationrecordsbyid', 'StationRecordsController@indexById');
    Route::put('/stationrecords/{id}', 'StationRecordsController@update');

    Route::get('/towersrecords', 'TowerRecordsController@index');
    Route::put('/towerrecords/{id}', 'TowerRecordsController@update');
    Route::put('/towerrecords', 'TowerRecordsController@store');
    Route::put('/towermessage/{id}', 'TowerRecordsController@updateMessage');

    Route::post('/feedback', 'FeedBackController@store');
    Route::get('/feedback', 'FeedBackController@index');
    Route::delete('/feedback/{id}', 'FeedBackController@destroy');


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




    Route::get('/loadstationdata', 'StationController@loadStationData');
    Route::post('/taskrequest', 'StationController@taskRequest');
    Route::put('/updatestationnotification/{id}', 'StationController@update');
    Route::put('/updatetimerinfo/{id}', 'StationController@editUpdate');

    Route::put('/stationname', 'StationController@reconPullbyname');
    Route::put('/stationnew', 'StationController@store');
    Route::put('/stationattackmessage/{id}', 'StationController@updateAttackMessage');
    Route::put('/stationmessage/{id}', 'StationController@updateMessage');


    Route::get('/ticklist', 'CorpController@index');

    Route::get('/allianceticklist', 'AllianceController@allianceTickList');

    Route::get('/structurelist', 'ItemController@index');
    Route::get('/towerlist', 'ItemController@towerlist');

    Route::get('/moons/{sysid}', 'MoonController@bySystem');

    Route::post('/ammorequest', 'AmmoRequestController@store');

    Route::get('/ammorequestrecords', 'AmmoRequestRecordsController@index');
    Route::get('/loadammorequestdata', 'AmmoRequestController@loadAmmoRequestData');
    Route::delete('/ammorequestdelete/{id}', 'AmmoRequestController@destroy');
    Route::post('/ammorequestupdate/{id}', 'AmmoRequestController@update');

    Route::post('/recontask', 'ReconTaskController@store');
    Route::get('/recontask', 'ReconTaskController@index');
    Route::get('/recontasksystems', 'ReconTaskSystemRecordsController@index');
    Route::put('recontasksystemtimeupdate/{id}', 'ReconTaskSystemController@update');
    Route::delete('/recontask/{id}', 'ReconTaskController@destroy');

    Route::get('/constellations', 'ConstellationsController@constellationlist');
    Route::post('/startcampaigns/{campid}/{name}', 'StartCampaignController@store');
    Route::get('/startcampaigns', 'StartCampaignController@index');
    Route::get('/startcampaignjoin', 'StartCampaignJoinController@index');
    Route::delete('/startcampaigns/{id}', 'StartCampaignController@destroy');
    Route::get('/startcampaignjoinbyid/{campid}', 'StartCampaignJoinController@indexByID');
    Route::get('/startcampaignsystemsrecords', 'StartCampaignSystemRecordsController@index');
    Route::put('/startcampaignsystemupdate/{id}/{campid}', 'StartCampaignSystemController@update');
    Route::put('/startcampaignsystemupdatetimer/{id}/{campid}', 'StartCampaignSystemController@updatetimer');
    Route::delete('/startcampaignsystemremovechar/{id}/{char}/{campid}', 'StartCampaignSystemController@removeChar');

    Route::get('/rcsheet', 'RcSheetController@index');
    Route::put('/rcfcuseradd/{id}', 'RcFcUsersController@addFCtoStation');
    Route::put('/rcfcuserremove/{id}', 'RcFcUsersController@removeFCtoStation');
    Route::put('/rcreconuseradd/{id}', 'RcReconUsersController@addRecontoStation');
    Route::put('/rcreconuserremove/{id}', 'RcReconUsersController@removeRecontoStation');
    Route::put('/rcgsoluseradd/{id}', 'RcGsolUsersController@addGsoltoStation');
    Route::put('/rcgsoluserremove/{id}', 'RcGsolUsersController@removeGsoltoStation');
    Route::put('finishrcstation/{id}', 'RcSheetController@stationdone');
    Route::get('/rcfc', 'RcFcUsersController@index');
    Route::put('/rcfcnew', 'RcFcUsersController@newfc');
    Route::delete('/rcfcdelete/{id}', 'RcFcUsersController@removeFC');
    Route::post('/rcfcadd/{id}', 'RcFcUsersController@addFCadd');
    Route::get('/rcregionlist', 'RcSheetController@rcSheetListRegion');
    Route::get('/rcTypelist', 'RcSheetController@rcSheetListType');
    Route::get('/rcStatuslist', 'RcSheetController@rcSheetListStatus');
    Route::put('/rcfixcorp/{id}', 'RcSheetController@fixcorp');
    Route::put('/rcfixalliance/{id}', 'RcSheetController@fixalliance');
    Route::put('/sheetmessage/{id}', 'RcSheetController@updateMessage');
    Route::get('/rcadminlogs', 'LoggingController@rcSheetLogging');
    Route::get('/stationlogs', "LoggingController@stationLogging");
    Route::put('/rcmovedone/{id}', 'StationController@rcMoveDone');
    Route::delete('/rcmovedonebad/{id}', 'StationController@destroy');

    Route::get('/chillsheet', 'ChillStationController@index');
    Route::get('/chillregionlist', 'ChillStationController@chillSheetListRegion');
    Route::get('/chillTypelist', 'ChillStationController@chillSheetListType');
    Route::get('/chillStatuslist', 'ChillstationController@chillSheetListStatus');
    Route::get('/chilltest', 'ChillStationController@test');
    Route::put('finishrcstationchill/{id}', 'ChillStationController@stationdone');
    Route::put('/chillupdatetimerinfo/{id}', 'ChillStationController@chillEditUpdate');
    Route::delete('/chilldelete/{id}', 'ChillStationController@destroy');
    Route::put('/chillupdatestationnotification/{id}', 'ChillStationController@update');


    Route::get('/welpsheet', 'WelpStationController@index');
    Route::get('/welpregionlist', 'WelpStationController@welpSheetListRegion');
    Route::get('/welpTypelist', 'WelpStationController@welpSheetListType');
    Route::get('/welpStatuslist', 'WelpstationController@welpSheetListStatus');
    Route::get('/welptest', 'WelpStationController@test');
    Route::put('finishrcstationwelp/{id}', 'WelpStationController@stationdone');
    Route::put('/welpupdatetimerinfo/{id}', 'WelpStationController@welpEditUpdate');
    Route::delete('/welpdelete/{id}', 'WelpStationController@destroy');
    Route::put('/welpupdatestationnotification/{id}', 'WelpStationController@update');




    Route::get('/alluserskeys', 'KeyTypeController@getAllUsersKeys');
    Route::get('/allkeyfleets', 'FleetTypeController@getAllKeyFleets');
    Route::get('/keys', 'KeyTypeController@index');
    Route::put('/keysremove', 'KeyTypeController@removeKey');
    Route::get('/fleets', 'FleetTypeController@index');
    Route::delete('/fleetdelete/{id}', 'FleetTypeController@destroy');
    Route::put('/fleetnew', 'FleetTypeController@store');
    Route::put('/keynew', 'KeyTypeController@store');
    Route::put('/keysadd', 'UserKeyJoinControllerController@store');
    Route::delete('/keydelete/{id}', 'KeyTypeController@destroy');
    Route::put('/fleetssadd', 'KeyFleetJoinControllerController@store');
    Route::put('/fleetsremove', 'KeyFleetJoinControllerController@removefleet');
    Route::put('/softdestory/{id}', 'StationController@softDestroy');
    Route::get('/coordRegionlist', 'CoordSheetController@coordSheetListRegion');
    Route::get('/coordItemlist', 'CoordSheetController@coordSheetListItem');
    Route::get('/coordStatuslist', 'CoordSheetController@coordSheetListStatus');
    Route::get('/coordsheet', 'CoordSheetController@index');
    Route::put('/editstationname/{id}', 'StationController@editStationNameReconCheck');
    Route::post('/userupdate/{id}', 'UserController@updateMessage');

    Route::get('/addmissingcorp/{name}', 'CorpController@addMissingCorp');

    ///
    Route::get('/solooperationlist', 'NewOperationsController@index');
    Route::put('/newcampaigns/{id}', 'NewCampaignsController@update');



    Route::get('/stationsheet', 'StationController@stationSheet');
    Route::get('/hotregion', 'HotRegionController@index');
    Route::post('/hotregionedit', 'HotRegionController@update');
});
