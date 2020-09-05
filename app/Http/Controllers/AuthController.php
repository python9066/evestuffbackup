<?php

namespace App\Http\Controllers;


use App\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Providers\RouteServiceProvider;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\User as AuthUser;

use function GuzzleHttp\json_decode;
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
        User::updateOrCreate(['id' => $userGice->id],['name' =>$userGice->name , 'token' =>$userGice->token , 'pri_grp' =>$userGice->user['pri_grp'], 'api_token' => Str::random(60)]);
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

        // dd($user);
    // $body = array(
    //     "displayName"=> "Eve Stuff",
    //     "redirectUris"=> [ "http://eve.test/oauth/callback", "http://evestuff.online/oauth/callback" ]
    // );

    // $body = json_encode($body);
    // // echo $body;
    // // dd($body);

    // $client = new Client();
    // $headers = [
    //     'Authorization' => 'Basic YzgwMDUzNWU3YzUyNDlkYTgxMDdkN2FjYzEzMWMzYjA6Q0lva1NHOG9BUUoybHZiTzJTVjJwNUV5NG5rbjZxaXc=',
    //     'Content-Type' => 'application/json'
    // ];

    // $reponse = $client->request('PATCH', 'https://gice.goonfleet.com/Api/Oauth/Application/',[
    //     'headers' => $headers,
    //     'body' => $body
    // ]);


    $body = array(
        "displayName"=> "Eve Stuff",
        "redirectUris"=> [ "http://eve.test/oauth/callback", "http://evestuff.online/oauth/callback" ]
    );

    $body = json_encode($body);
    // echo $body;
    // dd($body);

    $client = new Client();
    $headers = [
        'Authorization' => 'Basic YzgwMDUzNWU3YzUyNDlkYTgxMDdkN2FjYzEzMWMzYjA6Q0lva1NHOG9BUUoybHZiTzJTVjJwNUV5NG5rbjZxaXc=',
        'Content-Type' => 'application/json',
        'Accept' => "application/json",
    ];

    $reponse = $client->request('PATCH', 'https://gice.goonfleet.com/Api/Oauth/Application/',[
        'headers' => $headers,
        'body' => $body
    ]);
    $body = $reponse->getBody();
    // $body1 = json_decode($body, true);
    echo $body;
    dd($reponse, $body);

    }
}
