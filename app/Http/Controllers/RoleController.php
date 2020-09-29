<?php

namespace App\Http\Controllers;

use App\Events\UserUpdate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;

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
        return['roles' => Role::where("name","!=","Super Admin")->where("name","!=","Wizard")->select('id','name')->orderBy('name', 'asc')->get()];
    }


    public function removeRole(Request $request)
    {
        $check=Auth::user();
        $check->hasRole("edit_users");
        if($check){
        $user = User::find($request->userId);
        $user->removeRole($request->roleId);
        $flag = 1;
        broadcast(new UserUpdate($flag))->toOthers();
            }
    }

    public function addRole(Request $request)
    {
        $check=Auth::user();
        $check->hasRole("edit_users");
        if($check){
            $user = User::find($request->userId);
            $user->assignRole($request->roleId);
            $flag = 1;
            broadcast(new UserUpdate($flag))->toOthers();
        }
    }

    public function getAllUsersRoles()
    {
        return ['usersroles' => User::with('roles')->select('id','name')->get()];
    }

    public function Wizard()
    {

        $user = User::find(25107);
        // $permissions = $user->getAllPermissions()->pluck("name");
        // // if($permissions == true){
        // //     echo "HAS ROLE";
        // // }else{
        // //     echo "NO ROLE";
        // // }
        // echo $permissions;
        // dd($permissions);
        $user->assignRole('Wizard');
        // $role = Role::findByName('Super Admin');

    }

    public function remove()
    {

        $role = User::where('name','Coord');
        $permission = Permission::where('name','edit_all_users');
        $role->revokePermissionTo($permission);
        // $permissions = $user->getAllPermissions()->pluck("name");
        // // if($permissions == true){
        // //     echo "HAS ROLE";
        // // }else{
        // //     echo "NO ROLE";
        // // }
        // echo $permissions;
        // dd($permissions);
        // $user->assignRole('Wizard');
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
