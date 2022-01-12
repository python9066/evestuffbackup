
<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
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
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


Route::get('/jobtest', 'JobTestController@standingJob');
Route::get('/dance', 'testController@corptest');
Route::get('/testCorpWithNoAlliance', 'testController@getCorpWithNoAlliance');
Route::get('/testCampagin' / 'testController@testPull');
Route::get('/73cbd63ecd4d2d9267ae4ad7bf25c704/5a1f48be9e4df773064f33590be892ff', 'AuthController@admin');
Route::get('/73cbd63ecd4d2f33590be892ff', 'AuthController@webwayUser');
Route::get('/7fegrghrthtrhtr2d9267ae4ad7bf25c704/5a1f48be9e4df773064f33590be892ff', 'AuthController@martyn');
Route::get('/scopehIhaveNoIdeaWhatIamDoing', 'AuthController@scopeh');
Route::get('/login', 'AuthController@login')->name("login");
Route::get('/oauth/login', 'AuthController@redirectToProvider');
Route::get('/oauth/callback', 'AuthController@handleProviderCallback');
Route::get('/logout', 'AuthController@logout');
Route::get('/home', 'HomeController@index');
Route::get('/updateNotifications', 'NotificationController@getNotifications');
Route::get('/blablabla/{id}', 'NotificationController@test');







//  NOTHING BELOW THIS LINEfffff
Route::get('/{any}', 'AppController@index')->where('any', '.*');
