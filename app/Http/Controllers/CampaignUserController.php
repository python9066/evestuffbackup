<?php

namespace App\Http\Controllers;

use App\Events\CampaiganSystemUpdate;
use App\Models\CampaignUser;
use Illuminate\Http\Request;

class CampaignUserController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        CampaignUser::create($request->all());
        $flag = collect([
            'flag' => 1,
            'id' => $request->campaign_id
        ]);
        broadcast(new CampaiganSystemUpdate($flag))->toOthers();
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
        CampaignUser::find($id)->update($request->all());
        $data = CampaignUser::where('id',$id)->first();
        $flag = collect([
            'flag' => 1,
            'id' => $data->campaign_id
        ]);
        broadcast(new CampaiganSystemUpdate($flag))->toOthers();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = CampaignUser::where('id',$id)->first();
        $flag = collect([
            'flag' => 1,
            'id' => $data->campaign_id
        ]);
        CampaignUser::destroy($id);
        broadcast(new CampaiganSystemUpdate($flag))->toOthers();

    }
}
