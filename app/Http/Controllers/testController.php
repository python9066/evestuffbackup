<?php

namespace App\Http\Controllers;

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
        $data = $request;
        foreach ($data as $var){

            echo $var;

        }
    }
}
