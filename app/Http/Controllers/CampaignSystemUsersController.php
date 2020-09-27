<?php

namespace App\Http\Controllers;

use App\Events\CampaignUsersChanged;
use App\Models\Campaign;
use App\Models\CampaignSystemUsers;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CampaignSystemUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($campid)
    {

        $test = CampaignSystemUsers::with('user.name')->get();


        $test2 =$test;
        dd($test,$test2);
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
