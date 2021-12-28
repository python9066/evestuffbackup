
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



Route::get('/dance/{name}', 'testController@corptest');
Route::get('/73cbd63ecd4d2d9267ae4ad7bf25c704/5a1f48be9e4df773064f33590be892ff', 'AuthController@admin');
Route::get('/7fegrghrthtrhtr2d9267ae4ad7bf25c704/5a1f48be9e4df773064f33590be892ff', 'AuthController@martyn');
Route::get('/scopehIhaveNoIdeaWhatIamDoing', 'AuthController@scopeh');
// Route::get('/dances2','RoleController@getAllUsersRoles');
// Route::get('/timers/{campid}', 'CampaignSystemUsersController@index');
// Route::get('/getTimerData','TimerController@getTimerData');
// Route::get('/updateTimerData','TimerController@updateTimerData');
// Route::get('/test', 'RoleController@remove');
Route::get('/login', 'AuthController@login')->name("login");
Route::get('/oauth/login', 'AuthController@redirectToProvider');
Route::get('/oauth/callback', 'AuthController@handleProviderCallback');
Route::get('/logout', 'AuthController@logout');
Route::get('/home', 'HomeController@index');
// Route::get('/pannel/{any}', 'RoleController@addCord')->where('any', '.*');
Route::get('/updateNotifications', 'NotificationController@getNotifications');
Route::get('/blablabla/{id}', 'NotificationController@test');
// Route::get('/updateAlliances', 'AllianceController@updateAlliances');
// Route::get('/party2', 'HomeController@party2');
// Route::get('/helper', function () {
//     return Helper::displayName();
// });
// Route::get('/helper2', function () {
//     return Auth::user()->name;
// });
// Route::get('/367448c2da9ee714f64d0bce9dfd219fabf03dbccb1948969afea0d814c7e8d144/66cedf66cf26e0061eb2ca8ea6472c0c169f66', 'CronController@notifications');
// Route::get('/367448c2da9ee714f64d0bce9dffffffd219fabf8969afea0d814c7e8d144/66cedf66cf2a6472c0c169f66','CronController@timers');
//  Route::get('/3714f64d0bce9dfd219fabf03dbccb1948969afea0d814c7e8d144/66cedf66cf26e006172c0c169f66', 'CronController@alliances');





//  NOTHING BELOW THIS LINEfffff
Route::get('/{any}', 'AppController@index')->where('any', '.*');
