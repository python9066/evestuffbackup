<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\System;
use App\Models\Userlogging;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
    public function index()
  {
    $check = Auth::user();
    if ($check != null){
    Userlogging::create([
        'name' => Auth::user()->name
    ]);}
    $url = url()->current();
    // dd($url);
    session(['url' => $url]);
    $data = session()->all();
    dd($data);
    return view('/home');
  }

  public function test()
  {

      $system=System::all();
    //   dd($system);
      $hello = 'YO YO YO';
    return view('/test', compact('hello','system'));
  }

  public function saveAllianceData(Request $request)
  {
    dd($request);
  }
}
