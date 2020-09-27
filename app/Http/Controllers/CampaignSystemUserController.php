<?php

namespace App\Http\Controllers;

use App\Events\CampaignUsersChanged;
use App\Models\CampaignSystemUsers;
use Illuminate\Http\Request;

class CampaignSystemUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($campid)
    {
        $test = CampaignSystemUsers::where('campaign_id',$campid);
        dd($test);
        return [ 'users' => CampaignSystemUsers::with('user')->where('campaign_id',$campid)->get()];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $campid)
    {
        // dd($request);
        CampaignSystemUsers::create($request->all());
        $flag = collect([
            'flag' => 5,
            'id' => $campid
        ]);
        broadcast(new CampaignUsersChanged($flag))->toOthers();
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
    public function destroy($id, $campid)
    {
        CampaignSystemUsers::where('user_id',$id)->delete();
        $flag = collect([
            'flag' => 5,
            'id' => $campid
        ]);
        broadcast(new CampaignUsersChanged($flag))->toOthers();
    }
}
