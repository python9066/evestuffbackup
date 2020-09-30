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

    public function notifications(Request $data)
    {
        json_decode($data);
        dd($data);
        foreach ($data as $var){
            dd($var);
        }
    }
}
