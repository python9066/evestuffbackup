<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Alliance;

class AllianceController extends Controller
{
    public function saveAllianceIDs(Request $request)
    {
        $data = $request->json()->all();
        Alliance::insert(['id'=>$data]);
        dd($data);
        return view('/test',compact('data'));
        }
    }

