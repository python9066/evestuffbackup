<?php

namespace App\Http\Controllers;

use App\Auth;
use App\Client;
use App\User;
use DateTime;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

use function GuzzleHttp\json_decode;

class AuthController extends Controller
{

    public function redirectToProvider()
    {
        return Socialite::with('gice')->redirect();
    }
    public function handleProviderCallback()
    {
        $user = Socialite::with('gice')->user();
        User::updateOrCreate(['id' => $user->id],['name' =>$user->name , 'token' =>$user->token , 'pri_grp' =>$user->user['pri_grp']]);
        // $user->token;
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
