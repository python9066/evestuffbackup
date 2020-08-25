<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;

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

        $date = new DateTime();
        $date->modify("+20 minutes");
        $code = "4f8f8ad2b29e44cdb13f2f6d0f7d482d:QVyq0lie3rpCnGXLUBuV1JWr6rHh9z0RQDxWg6aI";
        $code = base64_encode($code);
        dd($code);
        return view('test', compact('date','code'));
    }
}
