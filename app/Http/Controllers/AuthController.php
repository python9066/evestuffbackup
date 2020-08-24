<?php

namespace App\Http\Controllers;

use App\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function getAuth()
    {
        $data= Auth::all();
        //dd($data);

        return $data;
    }
}
