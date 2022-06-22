<?php

namespace App\Http\Controllers;

use App\Models\FeedBack;
use App\Models\User;
use Illuminate\Http\Request;

class FeedBackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feedback = [];
        $feed = FeedBack::with('user')->get();
        foreach ($feed as $feed) {
            $data = [];
            $time = fixtime($feed->created_at);
            $data = [
                'id' => $feed->id,
                'user_id' => $feed->user_id,
                'user_name' => $feed->user->name,
                'text' => $feed->text,
                'created' => $time,
            ];
            array_push($feedback, $data);
        }
        // $test = User::has('feedback')->get();

        return ["feedback" => $feedback];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        FeedBack::create($request->all());
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
        FeedBack::destroy($id);
    }
}
