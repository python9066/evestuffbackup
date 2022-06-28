<?php

use App\Http\Controllers\AllianceController;
use App\Http\Controllers\AmmoRequestController;
use App\Http\Controllers\AmmoRequestRecordsController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignJoinsController;
use App\Http\Controllers\CampaignRecordsController;
use App\Http\Controllers\CampaignSolaSystemsController;
use App\Http\Controllers\CampaignSystemRecordsController;
use App\Http\Controllers\CampaignSystemsController;
use App\Http\Controllers\CampaignSystemUsersController;
use App\Http\Controllers\CampaignUserController;
use App\Http\Controllers\CampaignUserRecordsController;
use App\Http\Controllers\ChillStationController;
use App\Http\Controllers\ConstellationsController;
use App\Http\Controllers\CoordSheetController;
use App\Http\Controllers\CorpController;
use App\Http\Controllers\CustomCampaignsController;
use App\Http\Controllers\EveController;
use App\Http\Controllers\FeedBackController;
use App\Http\Controllers\FleetTypeController;
use App\Http\Controllers\HotRegionController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\KeyFleetJoinControllerController;
use App\Http\Controllers\KeyTypeController;
use App\Http\Controllers\LoggingController;
use App\Http\Controllers\MoonController;
use App\Http\Controllers\NewCampaignsController;
use App\Http\Controllers\NewOperationsController;
use App\Http\Controllers\NewSystemNodeController;
use App\Http\Controllers\NewUserNodeController;
use App\Http\Controllers\NodeJoinsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationRecordsController;
use App\Http\Controllers\OperationInfoController;
use App\Http\Controllers\OperationInfoSheetController;
use App\Http\Controllers\OperationUserController;
use App\Http\Controllers\RcFcUsersController;
use App\Http\Controllers\RcGsolUsersController;
use App\Http\Controllers\RcReconUsersController;
use App\Http\Controllers\RCSheet;
use App\Http\Controllers\RcSheetController;
use App\Http\Controllers\ReconTaskController;
use App\Http\Controllers\ReconTaskSystemController;
use App\Http\Controllers\ReconTaskSystemRecordsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StartCampaignController;
use App\Http\Controllers\StartCampaignJoinController;
use App\Http\Controllers\StartCampaignSystemController;
use App\Http\Controllers\StartCampaignSystemRecordsController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\StationRecordsController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\testController;
use App\Http\Controllers\TimerController;
use App\Http\Controllers\TowerRecordsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserKeyJoinControllerController;
use App\Http\Controllers\UserRolesRecordsController;
use App\Http\Controllers\WebWayStartSystemsContorller;
use App\Http\Controllers\WelpStationController;
use App\Models\Notification;
use App\Models\WebWay;
use GuzzleHttp\Middleware;
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
    Route::post('/webway', [WebWay::class, 'getWebway']);
    Route::post('/url', [AppController::class, 'url']);
    Route::post('/brois', [testController::class, 'notifications']);
    Route::get('/test', [testController::class, 'key']);
    Route::post('/testscoreupdate/{id}', [testController::class, 'testUpdateScore']);
    Route::post('/testscorerun', [testController::class, 'testRunScore']);
    Route::post('/testclearalldata', [testController::class, 'testClearCampaigns']);
    Route::get('/eveusercount', [EveController::class, 'playerCount']);
    Route::post('/rcInput', [RCSheet::class, 'RCInput']);
    Route::get('/reconpullregion', [StationController::class, 'reconRegionPull']);

    //HACKING NOTIFICATION APIS//
    Route::get('//notifications/{region_id}', [NotificationRecordsController::class, 'regionLink']);
    Route::get('/notifications', [NotificationRecordsController::class, 'index']);
    Route::put('//notifications/{id}', [Notification::class, 'update']);
    Route::put('/notificationsaddtime/{id}', [NotificationController::class, 'addTime']);

    //IHUB-TCU WINDOWS//
    Route::get('/timers', [TimerController::class, 'getTimerData']);
    Route::get('/timersregions', [TimerController::class, 'getTimerDataRegions']);

    //HACKING Campaign APIs
    Route::get('/campaigns', [CampaignRecordsController::class, 'index']);
    Route::get('/campaignsregion', [CampaignRecordsController::class, 'campaignslistRegion']);
    Route::get('/campaignslist', [CampaignRecordsController::class, 'campaignslist']);
    Route::put('/campaigns/{id}', [CampaignRecordsController::class, 'update']);

    //CAMPIGN USERS
    Route::get('/campaignusersrecords/{id}', [CampaignUserRecordsController::class, 'show']);
    Route::get('/campaignusersrecordsbychar/{id}', [CampaignUserRecordsController::class, 'bychar']);
    Route::put('/campaignusersrecords/{id}/{campid}', [CampaignUserRecordsController::class, 'update']);
    Route::post('/campaignusersrecords/{id}/{campid}', [CampaignUserRecordsController::class, 'store']);

    Route::post('/campaignusers/{campid}', [CampaignUserController::class, 'store']);
    Route::put('/campaignusers/{id}/{campid}', [CampaignUserController::class, 'update']);
    Route::put('/campaignusersadd/{id}/{campid}', [CampaignUserController::class, 'updateadd']);
    Route::put('/campaignusersremove/{id}/{campid}', [CampaignUserController::class, 'updateremove']);
    Route::delete('/campaignusers/{id}/{campid}/{siteid}', [CampaignUserController::class, 'destroy']);

    //Campagin NODES
    Route::get('/campaignsystemsrecords', [CampaignSystemRecordsController::class, 'index']);
    Route::get('/campaignsystemsrecords/{id}', [CampaignSystemRecordsController::class, 'show']);
    Route::put('/campaignsystemsrecords/{id}/{campid}', [CampaignSystemRecordsController::class, 'update']);
    Route::post('/campaignsystemsrecords/{id}/{campid}', [CampaignSystemRecordsController::class, 'store']);
    Route::delete('/campaginsystemsrecords/{id}/{campid}', [CampaignSystemRecordsController::class, 'destroy']);
    Route::post('/campaignpriority/{id}', [CampaignSystemRecordsController::class, 'updatePriority']);

    Route::post('/campaignsystemload', [CampaignSystemsController::class, 'load']);
    Route::post('/afterextranodeload', [CampaignSystemsController::class, 'afterExtraNodeLoad']);
    Route::post('/campaignsystems/{campid}', [CampaignSystemsController::class, 'store']);
    Route::put('/campaignsystems/{id}/{campid}', [CampaignSystemsController::class, 'update']);
    Route::put('/campaignsystemsnodemessage/{id}', [CampaignSystemsController::class, 'updateMessage']);
    Route::put('/campaignsystemsattackmessage/{id}', [CampaignSystemsController::class, 'updateAttackMessage']);
    Route::put('/campaignsystemsupdatetime/{id}/{campid}', [CampaignSystemsController::class, 'updatetime']);
    Route::delete('/campaignsystems/{id}/{campid}', [CampaignSystemsController::class, 'destroy']);
    Route::put('/campaignsystemremovechar/{campid}', [CampaignSystemsController::class, 'removechar']);
    Route::put('/campaignsystemmovechar/{campid}', [CampaignSystemsController::class, 'movechar']);
    Route::get('/campaignsystemcheckaddchar/{campid}', [CampaignSystemsController::class, 'checkAddChar']);
    Route::post('/campaignsystemuserskick/{campid}', [CampaignSystemsController::class, 'kickUser']);
    Route::get('/campaignsystemfinished/{campid}', [CampaignSystemsController::class, 'finishCampaign']);
    Route::get('/mcampaignsystemfinished/{campid}', [CampaignSystemsController::class, 'mfinishCampaign']);
    Route::put('/campaignsystemstidi/{sysid}/{campid}', [CampaignSystemsController::class, 'tidi']);
    Route::put('campaignsystemstidimulti/{sysid}/{campid}', [CampaignSystemsController::class, 'tidimulti']);

    Route::post('/multicampaigns/{campid}/{name}', [CustomCampaignsController::class, 'store']);
    Route::get('/multicampaigns', [CustomCampaignsController::class, 'index']);
    Route::delete('/multicampaigns/{id}', [CustomCampaignsController::class, 'destroy']);
    Route::post('/multicampaignsedit/{campid}/{name}', [CustomCampaignsController::class, 'edit']);

    //SYSTEM API
    Route::get('/systemsinconstellation/{id}', [SystemController::class, 'systemsinconstellation']);
    Route::get('/systemlist', [SystemController::class, 'index']);

    Route::get('/users', [AuthController::class, 'index']);
    Route::get('/userrolerecord', [UserRolesRecordsController::class, 'index']);
    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/allusersroles', [RoleController::class, 'getAllUsersRoles']);
    Route::put('/rolesadd', [RoleController::class, 'addRole']);
    Route::put('/rolesremove', [RoleController::class, 'removeRole']);
    Route::post('/campaignsystemusers/{campid}', [CampaignSystemUsersController::class, 'store']);
    Route::get('/campaignsystemusers/{campid}', [CampaignSystemUsersController::class, 'index']);
    Route::delete('/campaignsystemusers/{id}/{campid}', [CampaignSystemUsersController::class, 'destroy']);
    Route::get('/campaignjoin', [CampaignJoinsController::class, 'index']);
    Route::get('/campaignjoinbyid/{campid}', [CampaignJoinsController::class, 'indexByID']);
    Route::get('/campaignjoin/{id}', [CampaignJoinsController::class, 'show']);
    Route::get('/campaignjoinlist/{id}', [CampaignJoinsController::class, 'list']);
    Route::get('/campaignjoinsystems/{id}', [CampaignJoinsController::class, 'campaignSystems']);
    Route::get('/campaignsolasystems', [CampaignSolaSystemsController::class, 'index']);
    Route::put('/campaignsolasystems/{solaid}/{campid}', [CampaignSolaSystemsController::class, 'update']);
    Route::get('/nodejoin/{campid}', [NodeJoinsController::class, 'tableindex']);
    Route::post('/nodejoin/{campid}', [NodeJoinsController::class, 'store']);
    Route::put('/nodejoinupdate/{id}/{campid}', [NodeJoinsController::class, 'update']);
    Route::put('/removecharfromnode/{id}/{campid}', [NodeJoinsController::class, 'removeCharForNode']);
    Route::put('/addchartonodeadmin/{id}/{campid}', [NodeJoinsController::class, 'addCharToNodeAdmin']);
    Route::put('/deleteextranode/{id}/{campid}', [NodeJoinsController::class, 'deleteExtraNode']);
    Route::get('/stationrecords', [StationRecordsController::class, 'index']);
    Route::get('/stationrecordsbyid', [StationRecordsController::class, 'indexById']);
    Route::put('/stationrecords/{id}', [StationRecordsController::class, 'update']);
    Route::get('/towersrecords', [TowerRecordsController::class, 'index']);
    Route::put('/towerrecords/{id}', [TowerRecordsController::class, 'update']);
    Route::put('/towerrecords', [TowerRecordsController::class, 'store']);
    Route::put('/towermessage/{id}', [TowerRecordsController::class, 'updateMessage']);
    Route::post('/feedback', [FeedBackController::class, 'store']);
    Route::get('/feedback', [FeedBackController::class, 'index']);
    Route::delete('/feedback/{id}', [FeedBackController::class, 'destroy']);
    Route::post('/checkaddnode/{campid}', [LoggingController::class, 'NodeAdd']);
    Route::post('/checkdeletenode/{campid}', [LoggingController::class, 'NodeDelete']);
    Route::get('/checkjoinleavecampaign/{campid}/{charid}/{logtype}', [LoggingController::class, 'joinleaveCampaign']);
    Route::get('/mcheckjoinleavecampaign/{campid}/{charid}/{logtype}', [LoggingController::class, 'mjoinleaveCampaign']);
    Route::put('/checklastedchecked/{campid}', [LoggingController::class, 'lastchecked']);
    Route::put('/checkscout/{campid}', [LoggingController::class, 'systemscout']);
    Route::put('/checkaddremovechar/{campid}', [LoggingController::class, 'addremovechar']);
    Route::put('/checkroleaddremove', [LoggingController::class, 'addRemoveRoles']);
    Route::get('/checkcampaign/{campid}', [LoggingController::class, 'logCampaign']);
    Route::get('/checkadmin', [LoggingController::class, 'logAdmin']);
    Route::get('/loadstationdata', [StationController::class, 'loadStationData']);
    Route::post('/taskrequest', [StationController::class, 'taskRequest']);
    Route::put('/updatestationnotification/{id}', [StationController::class, 'update']);
    Route::put('/updatetimerinfo/{id}', [StationController::class, 'editUpdate']);
    Route::put('/stationname', [StationController::class, 'reconPullbyname']);
    Route::put('/stationnew', [StationController::class, 'store']);
    Route::put('/stationattackmessage/{id}', [StationController::class, 'updateAttackMessage']);
    Route::put('/stationmessage/{id}', [StationController::class, 'updateMessage']);
    Route::get('/ticklist', [CorpController::class, 'index']);
    Route::get('/allianceticklist', [AllianceController::class, 'allianceTickList']);
    Route::get('/structurelist', [ItemController::class, 'index']);
    Route::get('/towerlist', [ItemController::class, 'towerlist']);
    Route::get('/moons/{sysid}', [MoonController::class, 'bySystem']);
    Route::post('/ammorequest', [AmmoRequestController::class, 'store']);
    Route::get('/ammorequestrecords', [AmmoRequestRecordsController::class, 'index']);
    Route::get('/loadammorequestdata', [AmmoRequestController::class, 'loadAmmoRequestData']);
    Route::delete('/ammorequestdelete/{id}', [AmmoRequestController::class, 'destroy']);
    Route::post('/ammorequestupdate/{id}', [AmmoRequestController::class, 'update']);
    Route::post('/recontask', [ReconTaskController::class, 'store']);
    Route::get('/recontask', [ReconTaskController::class, 'index']);
    Route::get('/recontasksystems', [ReconTaskSystemRecordsController::class, 'index']);
    Route::put('/recontasksystemtimeupdate/{id}', [ReconTaskSystemController::class, 'update']);
    Route::delete('/recontask/{id}', [ReconTaskController::class, 'destroy']);
    Route::get('/constellations', [ConstellationsController::class, 'constellationlist']);
    Route::post('/startcampaigns/{campid}/{name}', [StartCampaignController::class, 'store']);
    Route::get('/startcampaigns', [StartCampaignController::class, 'index']);
    Route::get('/startcampaignjoin', [StartCampaignJoinController::class, 'index']);
    Route::delete('/startcampaigns/{id}', [StartCampaignController::class, 'destroy']);
    Route::get('/startcampaignjoinbyid/{campid}', [StartCampaignJoinController::class, 'indexByID']);
    Route::get('/startcampaignsystemsrecords', [StartCampaignSystemRecordsController::class, 'index']);
    Route::put('/startcampaignsystemupdate/{id}/{campid}', [StartCampaignSystemController::class, 'update']);
    Route::put('/startcampaignsystemupdatetimer/{id}/{campid}', [StartCampaignSystemController::class, 'updatetimer']);
    Route::delete('/startcampaignsystemremovechar/{id}/{char}/{campid}', [StartCampaignSystemController::class, 'removeChar']);
    Route::get('/rcsheet', [RcSheetController::class, 'index']);
    Route::put('/rcfcuseradd/{id}', [RcFcUsersController::class, 'addFCtoStation']);
    Route::put('/rcfcuserremove/{id}', [RcFcUsersController::class, 'removeFCtoStation']);
    Route::put('/rcreconuseradd/{id}', [RcReconUsersController::class, 'addRecontoStation']);
    Route::put('/rcreconuserremove/{id}', [RcReconUsersController::class, 'removeRecontoStation']);
    Route::put('/rcgsoluseradd/{id}', [RcGsolUsersController::class, 'addGsoltoStation']);
    Route::put('/rcgsoluserremove/{id}', [RcGsolUsersController::class, 'removeGsoltoStation']);
    Route::put('finishrcstation/{id}', [RcSheetController::class, 'stationdone']);
    Route::get('/rcfc', [RcFcUsersController::class, 'index']);
    Route::put('/rcfcnew', [RcFcUsersController::class, 'newfc']);
    Route::delete('/rcfcdelete/{id}', [RcFcUsersController::class, 'removeFC']);
    Route::post('/rcfcadd/{id}', [RcFcUsersController::class, 'addFCadd']);
    Route::get('/rcregionlist', [RcSheetController::class, 'rcSheetListRegion']);
    Route::get('/rcTypelist', [RcSheetController::class, 'rcSheetListType']);
    Route::get('/rcStatuslist', [RcSheetController::class, 'rcSheetListStatus']);
    Route::put('/rcfixcorp/{id}', [RcSheetController::class, 'fixcorp']);
    Route::put('/rcfixalliance/{id}', [RcSheetController::class, 'fixalliance']);
    Route::put('/sheetmessage/{id}', [RcSheetController::class, 'updateMessage']);
    Route::get('/rcadminlogs', [LoggingController::class, 'rcSheetLogging']);
    Route::get('/stationlogs', [LoggingController::class, 'stationLogging']);
    Route::put('/rcmovedone/{id}', [StationController::class, 'rcMoveDone']);
    Route::delete('/rcmovedonebad/{id}', [StationController::class, 'destroy']);
    Route::get('/chillsheet', [ChillStationController::class, 'index']);
    Route::get('/chillregionlist', [ChillStationController::class, 'chillSheetListRegion']);
    Route::get('/chillTypelist', [ChillStationController::class, 'chillSheetListType']);
    Route::get('/chillStatuslist', [ChillstationController::class, 'chillSheetListStatus']);
    Route::get('/chilltest', [ChillStationController::class, 'test']);
    Route::put('finishrcstationchill/{id}', [ChillStationController::class, 'stationdone']);
    Route::put('/chillupdatetimerinfo/{id}', [ChillStationController::class, 'chillEditUpdate']);
    Route::delete('/chilldelete/{id}', [ChillStationController::class, 'destroy']);
    Route::put('/chillupdatestationnotification/{id}', [ChillStationController::class, 'update']);
    Route::get('/welpsheet', [WelpStationController::class, 'index']);
    Route::get('/welpregionlist', [WelpStationController::class, 'welpSheetListRegion']);
    Route::get('/welpTypelist', [WelpStationController::class, 'welpSheetListType']);
    Route::get('/welpStatuslist', [WelpstationController::class, 'welpSheetListStatus']);
    Route::get('/welptest', [WelpStationController::class, 'test']);
    Route::put('finishrcstationwelp/{id}', [WelpStationController::class, 'stationdone']);
    Route::put('/welpupdatetimerinfo/{id}', [WelpStationController::class, 'welpEditUpdate']);
    Route::delete('/welpdelete/{id}', [WelpStationController::class, 'destroy']);
    Route::put('/welpupdatestationnotification/{id}', [WelpStationController::class, 'update']);
    Route::get('/alluserskeys', [KeyTypeController::class, 'getAllUsersKeys']);
    Route::get('/allkeyfleets', [FleetTypeController::class, 'getAllKeyFleets']);
    Route::get('/keys', [KeyTypeController::class, 'index']);
    Route::put('/keysremove', [KeyTypeController::class, 'removeKey']);
    Route::get('/fleets', [FleetTypeController::class, 'index']);
    Route::delete('/fleetdelete/{id}', [FleetTypeController::class, 'destroy']);
    Route::put('/fleetnew', [FleetTypeController::class, 'store']);
    Route::put('/keynew', [KeyTypeController::class, 'store']);
    Route::put('/keysadd', [UserKeyJoinControllerController::class, 'store']);
    Route::delete('/keydelete/{id}', [KeyTypeController::class, 'destroy']);
    Route::put('/fleetssadd', [KeyFleetJoinControllerController::class, 'store']);
    Route::put('/fleetsremove', [KeyFleetJoinControllerController::class, 'removefleet']);
    Route::put('/softdestory/{id}', [StationController::class, 'softDestroy']);
    Route::get('/coordRegionlist', [CoordSheetController::class, 'coordSheetListRegion']);
    Route::get('/coordItemlist', [CoordSheetController::class, 'coordSheetListItem']);
    Route::get('/coordStatuslist', [CoordSheetController::class, 'coordSheetListStatus']);
    Route::get('/coordsheet', [CoordSheetController::class, 'index']);
    Route::put('/editstationname/{id}', [StationController::class, 'editStationNameReconCheck']);
    Route::post('/userupdate/{id}', [UserController::class, 'updateMessage']);
    Route::get('/addmissingcorp/{name}', [CorpController::class, 'addMissingCorp']);

    ///

    Route::get('/solooperationlist', [NewOperationsController::class, 'index']);
    Route::put('/newcampaigns/{id}', [NewCampaignsController::class, 'update']);
    Route::get('/stationsheet', [StationController::class, 'stationSheet']);
    Route::get('/getregionlists', [HotRegionController::class, 'index']);
    Route::post('/hotregionedit/{id}', [HotRegionController::class, 'update']);
    Route::post('/updatesetting', [HotRegionController::class, 'updateSetting']);
    Route::get('/getwebwaystartsystems', [WebWayStartSystemsContorller::class, 'getSystemList']);
    Route::post('/updatewebwaystartsystems', [WebWayStartSystemsContorller::class, 'update']);
    Route::put('/stationsheetupdatewebway/{id}', [StationController::class, 'updateStationSheetWebway']);

    // TODO  Add checks to make sure that people should be doing this when they press the link

    Route::get('/operationinfo/{id}', [NewOperationsController::class, 'getInfo']);
    Route::put('/newcampaignusersremove/{id}/{opID}/{userid}', [OperationUserController::class, 'updateremove']);
    Route::put('/newcampaignusersadd/{id}/{opID}/{userid}', [OperationUserController::class, 'updateadd']);
    Route::post('/newcampaignusers/{opID}/{userid}', [OperationUserController::class, 'store']);
    Route::put('/newcampaignusers/{userid}/{opID}', [OperationUserController::class, 'edit']);
    Route::delete('/newcampaignusers/{id}/{opID}/{userid}', [OperationUserController::class, 'destroy']);
    Route::post('/addnode', [NewSystemNodeController::class, 'store']);
    Route::delete('/deletenode/{id}', [NewSystemNodeController::class, 'destroy']);
    Route::post('/addusertonode', [NewSystemNodeController::class, 'addCharToNode']);
    Route::post('/updatenodestats/{id}', [NewSystemNodeController::class, 'updateStatus']);
    Route::put('/addprimetimer/{id}', [NewUserNodeController::class, 'addTimer']);
    Route::put('/addtimertonode/{id}', [NewUserNodeController::class, 'addTimertoNode']);
    Route::put('/onthewayreadytogo/{opID}/{opUserID}', [OperationUserController::class, 'updateOnTheWayReadyToGO']);
    Route::post('/checkedat/{systemID}', [SystemController::class, 'checkedAt']);
    Route::post('/edittidi/{systemID}', [SystemController::class, 'editTidi']);
    Route::get('/newcampaignslist', [NewCampaignsController::class, 'campaignsList']);
    Route::post('/newoperation', [NewOperationsController::class, 'makeNewOperation']);
    Route::get('/operationlist', [NewOperationsController::class, 'getCustomOperationList']);
    Route::post('/editoperation', [NewOperationsController::class, 'edit']);
    Route::delete('/newoperation/{id}', [NewOperationsController::class, 'destroy']);
    Route::delete('/newdeleteextanode/{id}', [NewUserNodeController::class, 'destroy']);
    Route::post('/addcharadmin', [NewSystemNodeController::class, 'addUserToNodeAdmin']);
    Route::post('/sendadduseroverlay/{opID}/{type}', [NewOperationsController::class, 'sendAddCharOverlay']);
    Route::post('/setreadonly/{opID}', [NewOperationsController::class, 'changeReadyOnly']);
    Route::post('/newcampaignpriority/{id}', [NewOperationsController::class, 'updatePriority']);

    Route::post('/operationinfosheet', [OperationInfoController::class, 'store']);
    Route::get('/operationinfosheet', [OperationInfoController::class, 'index']);

    Route::get('/operationinfopage/{id}', [OperationInfoSheetController::class, 'index']);
    Route::put('/operationinfopage/{id}', [OperationInfoSheetController::class, 'update']);
});
