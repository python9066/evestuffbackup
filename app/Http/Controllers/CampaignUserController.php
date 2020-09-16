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
    public function store(Request $request, $campid)
    {

        CampaignUser::create($request->all());
        $flag = collect([
            'flag' => 1,
            'id' => $campid
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
    public function update(Request $request, $id, $campid)
    {
        CampaignUser::find($id)->update($request->all());
        $flag = collect([
            'flag' => 1,
            'id' => $campid
        ]);
        broadcast(new CampaiganSystemUpdate($flag))->toOthers();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $campid)
    {

        CampaignUser::destroy($id);
        $flag = collect([
            'flag' => 1,
            'id' => $campid
        ]);
        broadcast(new CampaiganSystemUpdate($flag))->toOthers();

    }
}
