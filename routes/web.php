
<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ESITokensController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobTestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\testController;
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

// Route::get('/jobtest', 'JobTestController@standingJob');
Route::get('/jobtest', [JobTestController::class, 'standingJob']);
Route::get('/testpusher', [testController::class, 'testPusher']);
Route::get('/testRunScore', [testController::class, 'testRunScore']);
Route::get('/teststatus', [testController::class, 'testEveStatus']);
Route::get('/stationrecordtest/{id}', [testController::class, 'testStationRecords']);
Route::get('/testGetAliiance/{id}', [testController::class, 'testGetAlliance']);
Route::get('/testGetAliianceJob/{id}', [JobTestController::class, 'jobAllianceTest']);
Route::get('/testGetCorpJob/{id}', [JobTestController::class, 'jobCorpTest']);
// Route::get('/dance', [testController::class, 'corptest']);
Route::get('/testCorpWithNoAlliance', [testController::class, 'getCorpWithNoAlliance']);
Route::get('/testCampagin', [testController::class, 'testPull']);
Route::get('/populatenewcampaignsystem', [testController::class, 'popualteCampaignSystemTable']);
Route::get('/removefc', [testController::class, 'removeFC']);
Route::get('/hitherealso', [testController::class, 'horizon']);
Route::get('/hithere', [testController::class, 'prequal']);
Route::get('/testsolooperstions', [testController::class, 'getSoloOperations']);
Route::get('/campaignlisttest', [testController::class, 'campaginListTest']);
Route::get('/campaigntest', [testController::class, 'campaginTest']);
Route::get('/testUsers', [testController::class, 'key']);
Route::get('/testpull', [testController::class, 'testUpdateScore']);
Route::get('/nametoid/{name}', [testController::class, 'nameToID']);
Route::get('/adashd', [testController::class, 'adashDScan']);
Route::get('/danktest', [testController::class, 'dankDoc']);
Route::get('/testlog', [testController::class, 'testLogs']);
Route::get('testNotes', [testController::class, 'testNotes']);

Route::get('/73cbd63ecd4d2d9267ae4ad7bf25c704/5a1f48be9e4df773064f33590be892ff', [AuthController::class, 'admin']);
Route::get('/73cbd63ecd4d704/5a1f48be9e4df773064f33590be892ff', [AuthController::class, 'borisToken']);
Route::get('/73cbd63ecd4d2f33590be892ff', [AuthController::class, 'webwayUser']);
Route::get('/7fegrghrthtrhtr2d9267ae4ad7bf25c704/5a1f48be9e4df773064f33590be892ff', [AuthController::class, 'martyn']);
Route::get('/scopehIhaveNoIdeaWhatIamDoing', [AuthController::class, 'testPusher']);
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/oauth/login', [AuthController::class, 'redirectToProvider']);
Route::get('/oauth/callback', [AuthController::class, 'handleProviderCallback']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/home', [HomeController::class, 'index']);
Route::get('/updateNotifications', [NotificationController::class, 'getNotifications']);
Route::get('/blablabla/{id}', [NotificationController::class, 'test']);



Route::get('esi/add', [
    'as' => 'esi.add',
    'uses' => ESITokensController::class, 'redirectToProvider'
]);

Route::get('esi/callback', [ESITokensController::class, 'handleProviderCallback']);


// Route::get('/monty', [AuthController::class, 'monty']);
//  NOTHING BELOW THIS LINEfffff
//'ESITokensController@redirectToProvider',
// Route::get('/{any}', 'AppController@index')->where('any', '.*');
Route::get('/{any}', [AppController::class, 'index'])->where('any', '.*');
