<?php

namespace App\Http\Controllers;

use GuzzleHttp\Utils;
use Illuminate\Http\Request;
use utils\Helper\Helper;

class testController extends Controller
{
    public function index()
    {
        return view('test2');
    }

    public function notifications(Request $request)
    {
        Utils::jsonDecode($request, true);
        dd($request);
        foreach ($request as $var){
            dd($var);
        }
    }
}
