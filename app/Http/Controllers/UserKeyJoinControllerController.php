<?php

namespace App\Http\Controllers;

use App\Models\UserKeyJoin;
use App\Models\UserKeyJoinController;
use Illuminate\Http\Request;

class UserKeyJoinControllerController extends Controller
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
        UserKeyJoin::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserKeyJoinController  $userKeyJoinController
     * @return \Illuminate\Http\Response
     */
    public function show(UserKeyJoinController $userKeyJoinController)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserKeyJoinController  $userKeyJoinController
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserKeyJoinController $userKeyJoinController)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserKeyJoinController  $userKeyJoinController
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserKeyJoinController $userKeyJoinController)
    {
        //
    }
}
