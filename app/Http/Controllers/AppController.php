<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index()
  {
    return view('/home');
  }

  public function test()
  {

      $system=Syste::all();
    //   dd($system);
      $hello = 'YO YO YO';
    return view('/test', compact('hello','system'));
  }

  public function saveAllianceData(Request $request)
  {
    dd($request);
  }
}
