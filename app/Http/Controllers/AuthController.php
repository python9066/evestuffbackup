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




        // dd($userGice->grp);


        // User::updateOrCreate(['id' => $userGice->sub], ['name' => $userGice->name, 'token' => $userGice->token, 'pri_grp' => $userGice->user['pri_grp'], 'api_token' => Str::random(60)]);
        User::updateOrCreate(['id' => $userGice->sub], ['name' => $userGice->name, 'api_token' => Str::random(60)]);

        $user = User::where('id', $userGice->sub)->first();
        $this->purgeRoles($user);
        if (isset($userGice->grp)) {
            $roles = $userGice->grp;
            if (is_array($roles)) {

                foreach ($roles as $role) {

                    $this->addRoles($user, $role);
                }
            } else {
                $this->addRoles(
                    $user,
                    $roles
                );
            };
            // dd(is_array($roles));

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

    public function scopeh()
    {


        User::updateOrCreate(['id' => 999999999], ['name' => 'Schpeh The Hero', 'token' => '99999999999999999999999999999', 'pri_grp' => 5, 'api_token' => Str::random(60)]);
        $user = User::where('id', 999999999)->first();
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

    /*
    title -> gice_id -> site_id

    DIrector -> 8 -> 13
    gsfoe -> 1048 ->
    skirmish fc -> 28 -> 12
    gsol -> 47 -> 17
    scout -> 195 -> 7
    ops -> 231 -> 6
    recon -> 184 -> 5
    coord -> 494 -> 4

    */


    public function purgeRoles($user)
    {
        $user->removeRole(4); // Coord
        $user->removeRole(5); // Recon
        $user->removeRole(6); // ops
        $user->removeRole(13); // Director
        $user->removeRole(14); // Recon Leader
        $user->removeRole(16); // GSFOE Leader
        $user->removeRole(17); // GSOL
        $user->removeRole(12); // SC
    }

    public function addRoles($user, $role_id)
    {
        if ($role_id == 494) {

            // function to assign coord role
            $user->assignRole(4);
        }

        if ($role_id == 184) {

            // function to assign recon role
            $user->assignRole(5);
        }

        if ($role_id == 231) {

            // function to assign ops role
            $user->assignRole(6);
        }

        if ($role_id == 8) {

            // function to assign director role
            $user->assignRole(13);
        }

        if ($role_id == 1094) {

            // function to assign recon leader
            $user->assignRole(14);
        }

        if ($role_id == 47) {

            // function to assign gsol role
            $user->assignRole(17);
        }

        if ($role_id == 1045) {

            // function to assign GSFOE Leader role
            $user->assignRole(16);
        }

        if ($role_id == 28) {

            // function to assign FC role
            $user->assignRole(12);
        }
    }
}
