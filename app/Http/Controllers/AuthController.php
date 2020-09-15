<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Providers\RouteServiceProvider;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use function GuzzleHttp\json_encode;

class AuthController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = RouteServiceProvider::HOME;
    public function redirectToProvider()
    {
        return Socialite::with('gice')->redirect();
    }
    public function handleProviderCallback()
    {

        $userGice = Socialite::with('gice')->user();
        // dd($userGice);
        User::updateOrCreate(['id' => $userGice->id], ['name' => $userGice->name, 'token' => $userGice->token, 'pri_grp' => $userGice->user['pri_grp'], 'api_token' => Str::random(60)]);
        $user = User::where('id', $userGice->id)->first();
        Auth::login($user, true);

        return redirect('/notifications');
    }

    public function admin()
    {


        User::updateOrCreate(['id' => 5], ['name' => 'admin', 'token' => '456456456456456', 'pri_grp' => 5, 'api_token' => Str::random(60)]);
        $user = User::where('id', 5)->first();
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

    public function test()
    {
        $test = now()->modify('-3 days');

    }
}
