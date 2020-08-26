<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use utils\Helper\Helper;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function party()
    {
        Helper::authcheck();
        $url = 'https://esi.evetech.net/latest/characters/717568371/notifications/';
        $data = Helper::authpull($url);
        //dd($data);
        foreach ($data as $var){
        $raw = $var['text'];
        $test = $var['text'];
        $test = explode("\n", $test);
        array_pop($test);

        // $test =
        dd($test,$raw,$data);
        }
        return $raw;




    }
}



