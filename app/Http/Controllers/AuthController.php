<?php

namespace App\Http\Controllers;


use App\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\User as AuthUser;

use function GuzzleHttp\json_decode;
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
        User::updateOrCreate(['id' => $userGice->id],['name' =>$userGice->name , 'token' =>$userGice->token , 'pri_grp' =>$userGice->user['pri_grp']]);
        $user = User::where('id',$userGice->id)->first();
        Auth::login($user,true);

        return redirect('/notifications');


        // $user->token;
        // $user = New AuthUser;
        // $user->id = $userGice->id;
        // $user->name = $userGice->name;
        // $user->token = $userGice->token;
        // $user->pir_grp = $userGice->user['pri_grp'];
        // dd($user);

    }

    public function test(){
        $token = "8jugStvBH05tBdWvy6bqX2KZ95wZcA-rWSqwzTCp9Ls";
        $user = Socialite::with('gice')->userFromToken($token);
        // dd($user);

    // $client = new GuzzleHttpClient();
    // $headers = [
    //     'Authorization' => 'Bearer hawMqlMyB9rD7Nr53xgy-MmThCaJCKMAfTLM7wnELiI',
    //     'Content-Type' => 'application/json'
    // ];

    // $reponse = $client->request('POST', 'https://esi.goonfleet.com/oauth/token',[
    //     'headers' => $headers
    // ]);

    // dd($reponse);

    }
}
