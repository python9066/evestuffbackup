<?php

namespace App\Http\Controllers;

use App\Models\CampaignSystem;
use App\Events\CampaignSystemUpdate;
use App\Models\CampaignSolaSystem;
use App\Models\CampaignUser;
use Illuminate\Http\Request;

class CampaignSystemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newlfwfwy created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $campid)
    {

        CampaignSystem::create($request->all());
        $flag = collect([
            'flag' => 2,
            'id' => $campid,
        ]);
        broadcast(new CampaignSystemUpdate($flag))->toOthers();
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
    public function update(Request $request, $id, $campid)
    {
        $system_id = CampaignSystem::where('id', $id)->value('system_id');
        $camp = CampaignSystem::where('id', $id)->value('campaign_id');
        $tidi = CampaignSolaSystem::where('campaign_id', $camp)->where('system_id', $system_id)->value('tidi');
        $difference_in_seconds = strtotime($request->end_time) - strtotime($request->input_time); //28800

        CampaignSystem::where('id', $id)->update($request->all());
        $flag = collect([
            'flag' => 2,
            'id' => $campid
        ]);
        broadcast(new CampaignSystemUpdate($flag));
        dd($difference_in_seconds, $system_id, $camp, $tidi);
    }

    public function removechar(Request $request, $campid)
    {
        $node = CampaignSystem::where('campaign_id', $request->campaign_id)
            ->where('system_id', $request->system_id)
            ->where('campaign_user_id', $request->campaign_user_id)->first();



        if ($node != null) {
            $node->update(['campaign_user_id' =>  null]);
            $node->save();
            $test = CampaignSystem::where('campaign_id', $request->campaign_id)
                ->where('system_id', $request->system_id)->get();



            $flag = collect([
                'flag' => 2,
                'id' => $campid
            ]);
            broadcast(new CampaignSystemUpdate($flag))->toOthers();
        }
    }

    public function movechar(Request $request, $campid)
    {

        $node = CampaignSystem::where('campaign_user_id', $request->id)->first();

        if ($node != null) {
            $node->update(['campaign_user_id' =>  null, 'campaign_system_status_id' => 1, 'end_time' => null]);
            $node->save();
            $test = CampaignSystem::where('campaign_id', $request->campaign_id)
                ->where('system_id', $request->system_id)->get();

            $flag = collect([
                'flag' => 2,
                'id' => $campid
            ]);
            broadcast(new CampaignSystemUpdate($flag))->toOthers();
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $campid)
    {

        CampaignSystem::destroy($id);
        $flag = collect([
            'flag' => 3,
            'id' => $campid
        ]);
        broadcast(new CampaignSystemUpdate($flag))->toOthers();
    }

    public function checkAddChar($campid)
    {


        $flag = collect([
            'flag' => 5,
            'id' => $campid
        ]);
        broadcast(new CampaignSystemUpdate($flag))->toOthers();
    }

    public function kickUser(Request $request, $campid)
    {
        $flag = collect([
            'flag' => 6,
            'id' => $campid,
            'user_id' => $request['user_id']
        ]);
        broadcast(new CampaignSystemUpdate($flag))->toOthers();
    }

    public function finishCampaign($campid)
    {
        $flag = collect([
            'flag' => 7,
            'id' => $campid,
        ]);
        broadcast(new CampaignSystemUpdate($flag))->toOthers();
    }
    // public function destroy($id)
    // {

    //
    //     dd($data);
    //     $flag = $data->campaigan_id;
    // $flag = collect([
    //     'flag' => 2,
    //     'id' => $data->campaigan_id
    // ]);
    //     CampaignSystem::destroy($id);
    //     CampaignUser::where('campaign_system_id',$id)->update(['campaign_system_id' => null]);
    //
    //     $flag =null;
    //     $flag = collect([
    //         'flag' => 3,
    //         'id' => $data->campaigan_id
    //     ]);
    //     broadcast(new CampaignSystemUpdate($flag))->toOthers();

    // }
}
