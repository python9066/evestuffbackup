<?php

namespace App\Http\Controllers;

use App\Events\TowerChanged;
use App\Events\TowerDelete;
use App\Events\TowerNew;
use App\Models\Tower;
use App\Models\TowerRecord;
use Illuminate\Http\Request;

class TowerRecordsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ['towers' => TowerRecord::all()];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = Tower::where('id', '<', 10000)->min('id');
        if ($id == null) {
            $id = 1;
        } else {
            $id = $id + 1;
        }

        $new = Tower::create($request->all());
        $message = TowerRecord::where('id', $new->id)->first();
        $flag = collect([
            'message' => $message
        ]);

        broadcast(new TowerNew($flag));
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
        Tower::find($id)->update($request->all());
        $message =  TowerRecord::find($id);
        if ($message->status_id != 10) {
            $flag = collect([
                'message' => $message,
            ]);
            broadcast(new TowerChanged($flag));
        }
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
