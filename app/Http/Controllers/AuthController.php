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
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AuthController extends Controller
{
    use AuthenticatesUsers;
    use HasRoles;
    protected $redirectTo = RouteServiceProvider::HOME;
    public function redirectToProvider()
    {
        return Socialite::with('gice')->redirect();
    }
    public function handleProviderCallback()
    {
        $flag = 0;
        $userGice = Socialite::with('gice')->user();
        $check = User::where('id', $userGice->id)->get()->count();
        dd($check);
        if ($check != 1) {
            $flag = 1;
        };

        // User::updateOrCreate(['id' => $userGice->id], ['name' => $userGice->name, 'token' => $userGice->token, 'pri_grp' => $userGice->user['pri_grp'], 'api_token' => Str::random(60)]);
        User::updateOrCreate(['id' => $userGice->id], ['name' => $userGice->name, 'token' => $userGice->token, 'api_token' => Str::random(60)]);
        $user = User::where('id', $userGice->id)->first();
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
}
