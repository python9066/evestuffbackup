<?php

namespace App\Http\Controllers;

use App\Models\CampaignJoin;
use App\Models\CustomCampaign;
use Illuminate\Http\Request;

class CustomCampaignsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = [];
        $data = CustomCampaign::where('status_id',"<",3)->with("status")->get();
        foreach ($data as $data){
            $data = [
                'id' => $data['id'],
                'name' => $data['name'],
                'status_id' => $data['status_id'],
                'status_name' => $data['status']['name']
            ];
            array_push($data,$list);
        };

        return ['campaigns' => $data];

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $campid, $name)
    {
        $data = $request->all();
        // dd($data);
        CustomCampaign::create(['id' => $campid, 'name' => $name]);
        foreach ($data as $data){
            // dd($data);
        CampaignJoin::create(['custom_campaign_id' => $campid, 'campaign_id' => $data]);

        }
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
