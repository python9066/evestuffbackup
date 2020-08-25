<?php

namespace App\Http\Controllers;

use App\Auth;
use App\Client;
use DateTime;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function getAuth()
    {
        $data= Auth::all();
        //dd($data);

        return $data;
    }

    public function authcheck()
    {
        $data= Auth::first();
        $expire_date = new DateTime($data->expire_date);
        $date= new DateTime();
        $date = $date->modify("+20 minutes");
        // $date = $date->format('Y-m-d H:i:s');

        if($date > $expire_date){
            $data = [false];
        }else{
            $data = [true];
        }
// dd($data);
       return $data;
    }

    public function authURL()
    {
        $client=Client::first();
        $auth=Auth::first();

        $url = 'https://login.eveonline.com/oauth/token?grant_type=refresh_token&refresh_token='.$auth->refresh_token;
        $url = urlencode($url);
        $code = $client->code;
        $data = [$url, $code];
        dd($data);




       return $data;
    }




}
