<?php

namespace App\Http\Controllers;

use App\Events\UserUpdate;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Providers\RouteServiceProvider;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use function GuzzleHttp\json_encode;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AuthController extends Controller
{
    use AuthenticatesUsers;
    use HasRoles;
    use HasPermissions;
    protected $redirectTo = RouteServiceProvider::HOME;
    public function redirectToProvider()
    {
        return Socialite::with('gice')->redirect();
    }
    public function handleProviderCallback()
    {
        $flag = 0;
        $userGice = Socialite::with('gice')->user();
        // dd($userGice);
        $check = User::where('id', $userGice->sub)->get()->count();
        if ($check != 1) {
            $flag = 1;
        };




        // dd($g);


        // User::updateOrCreate(['id' => $userGice->sub], ['name' => $userGice->name, 'token' => $userGice->token, 'pri_grp' => $userGice->user['pri_grp'], 'api_token' => Str::random(60)]);
        User::updateOrCreate(['id' => $userGice->sub], ['name' => $userGice->name, 'api_token' => Str::random(60)]);

        $user = User::where('id', $userGice->sub)->first();
        $this->purgeRoles($user);
        if (isset($userGice->grp)) {
            $roles = $userGice->grp;

            foreach ($roles as $role) {

                $this->addRoles($user, $role);
            }
        };



        Auth::login($user, true);

        if ($flag == 1) {
            broadcast(new UserUpdate($flag))->toOthers();
        }
        $url = session('url');
        if ($url == null) {
            $url = '/notifications';
        }

        return redirect($url);
    }

    public function admin()
    {


        User::updateOrCreate(['id' => 5], ['name' => 'admin', 'token' => '456456456456456', 'pri_grp' => 5, 'api_token' => Str::random(60)]);
        $user = User::where('id', 5)->first();
        Auth::login($user, true);

        return redirect('/notifications');
    }

    public function martyn()
    {


        User::updateOrCreate(['id' => 99999999], ['name' => 'martn', 'token' => '9999999999999999999999999', 'pri_grp' => 5, 'api_token' => Str::random(60)]);
        $user = User::where('id', 99999999)->first();
        Auth::login($user, true);

        return redirect('/notifications');
    }

    public function logout()
    {
        Auth::logout();
        return view('auth.login');
    }

    public function login()
    {

        return view('auth.login');
    }

    public function index()
    {
        return ['users' => User::select('id', 'name')->get()];
    }


    public function purgeRoles($user)
    {
        $user->removeRole(4);
        $user->removeRole(5);
        $user->removeRole(6);
    }

    public function addRoles($user, $role_id)
    {
        if ($role_id == 494) {

            // function to assign coord role
            $user->assignRole(4);
        }

        if ($role_id == 184) {

            // function to assign coord role
            $user->assignRole(5);
        }

        if ($role_id == 231) {

            // function to assign coord role
            $user->assignRole(6);
        }
    }
}
