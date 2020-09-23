<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class RoleController extends Controller
{
    use HasRoles;
    use HasPermissions;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return['roles' => Role::where("name","!=","Super Admin")->select('id','name')->get()];
    }


    public function removeRole(Request $request)
    {
        $user = User::find($request->userId);
        $user->removeRole($request->roleId);
    }

    public function addRole(Request $request)
    {


        $user = User::find($request->userId);
        $user->assignRole($request->roleId);
    }

    public function getAllUsersRoles()
    {
        return ['usersroles' => User::with('roles')->select('id','name')->get()];
    }

    public function addCord()
    {

        $user = Auth::user();
        // $permissions = $user->getAllPermissions()->pluck("name");
        // // if($permissions == true){
        // //     echo "HAS ROLE";
        // // }else{
        // //     echo "NO ROLE";
        // // }
        // echo $permissions;
        // dd($permissions);
        $user->assignRole('Cord');
        // $role = Role::findByName('Super Admin');

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
        //
    }


}
