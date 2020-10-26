<?php

namespace App\Http\Controllers;

use App\Models\CampaignJoin;
use App\Models\CampaignSystem;
use App\Models\CampaignSystemUsers;
use App\Models\CampaignUser;
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
        $pull = CustomCampaign::where('status_id',"<",3)->with("status")->get();
        foreach ($pull as $pull){
            $data = [];
            $data = [
                'id' => $pull['id'],
                'name' => $pull['name'],
                'status_id' => $pull['status_id'],
                'status_name' => $pull['status']['name']
            ];
            array_push($list,$data);
        };

        return ['campaigns' => $list];

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

    public function edit(Request $request, $campid, $name)
    {

        CampaignJoin::where('custom_campaign_id',$campid)->delete();
        $data = $request->all();
        // dd($data);
        CustomCampaign::find($campid)->update(['name' => $name]);
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
        CustomCampaign::destroy($id);
        CampaignJoin::where('custom_campaign_id',$id)->delete();
        CampaignSystem::where('custom_campaign_id',$id)->delete();
        CampaignSystemUsers::where('custom_campaign_id',$id)->delete();
        CampaignUser::where('campaign_id',$id)->delete();



    }
}
