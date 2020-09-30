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
        $request->toArray();
        dd($request);
        foreach ($request as $var){
            dd($var);
        }
    }
}
