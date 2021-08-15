<?php

namespace App\Http\Controllers;

use App\Models\KeyFleetJoin;
use App\Models\KeyType;
use App\Models\User;
use App\Models\UserKeyJoin;
use Illuminate\Http\Request;

class KeyTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ['keys' => KeyType::where('id', '>', 0)->select('id', 'name')->orderBy('name', 'asc')->get()];
    }

    public function getAllUsersKeys()
    {
        return ['userskeys' => User::with('keys')->select('id', 'name')->get()];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        KeyType::create($request->all());
    }

    public function removeKey(Request $request)
    {
        UserKeyJoin::where('key_type_id', $request->key_type_id)->where('user_id', $request->user_id)->delete();
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
        KeyType::find($id)->delete();
        UserKeyJoin::where('key_type_id', $id)->delete();
        KeyFleetJoin::where('key_type_id', $id)->delete();
    }
}
