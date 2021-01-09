<?php

namespace App\Http\Controllers;

use App\Models\CampaignSolaSystem;
use App\Models\User;
use Illuminate\Http\Request;

class CampaignSolaSystemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = [];
        $pull = CampaignSolaSystem::all();
        foreach ($pull as $pull) {
            $checker_name = User::where('id', $pull['last_checked_user_id'])->select('name')->get();
            // if ($checker_name->count() == 0) {
            //     $checker_name = null;
            // } else {
            //     $checker_name = $checker_name->name;
            // }
            $supervier_name = User::where('id', $pull['supervisor_id'])->select('name')->get();
            dd($checker_name, $supervier_name);
            if ($supervier_name->count() == 0) {
                $supervier_name = null;
            } else {
                $supervier_name = $supervier_name['name'];
            }

            $data1 = [];
            $data1 = [
                "id" => $pull['id'],
                "system_id" => $pull['system_id'],
                "campaign_id" => $pull['campaign_id'],
                "supervisor_id" => $pull['supervisor_id'],
                "supervier_user_name" => $supervier_name,
                "last_checked_user_id" => $pull['last_checked_user_id'],
                "last_checked_user_name" => $checker_name,
                "last_checked" => $pull['last_checked'],
            ];
            array_push($data, $data1);
        }
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        // return ["data" => $data];
    }

    // dd($data);


    // return ['data' =>]


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
