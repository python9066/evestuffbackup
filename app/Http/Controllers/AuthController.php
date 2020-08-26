<?php

namespace App\Http\Controllers;

use App\Auth;
use App\Client;
use DateTime;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Http\Request;

use function GuzzleHttp\json_decode;

class AuthController extends Controller
{

    public function getAuth()
    {
        $data = Auth::all();
        //dd($data);

        return $data;
    }

    public function AuthURL()
    {




        //dd($test);
    }
}
