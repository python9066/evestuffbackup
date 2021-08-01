<?php

namespace App\Http\Controllers;

use App\Events\ReconTimerUpdate;
use App\Models\ReconTaskSystems;
use Illuminate\Http\Request;

class ReconTaskSystemController extends Controller
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
        ReconTaskSystems::find($id)->update(['user_id' => null]);
        ReconTaskSystems::find($id)->update($request->all());
        $task_id = ReconTaskSystems::find($id)->value('recon_task_id');
        dd($task_id);
        $flag = collect([
            'id' => $id,
        ]);
        broadcast(new ReconTimerUpdate($flag));
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
