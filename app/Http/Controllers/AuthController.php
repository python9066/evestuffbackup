<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Providers\RouteServiceProvider;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use function GuzzleHttp\json_encode;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AuthController extends Controller
{
    use AuthenticatesUsers;
    use HasRoles;
    protected $redirectTo = RouteServiceProvider::HOME;
    public function redirectToProvider()
    {
        return Socialite::with('gice')->redirect();
    }
    public function handleProviderCallback()
    {

        $userGice = Socialite::with('gice')->user();
        // dd($userGice);
        User::updateOrCreate(['id' => $userGice->id], ['name' => $userGice->name, 'token' => $userGice->token, 'pri_grp' => $userGice->user['pri_grp'], 'api_token' => Str::random(60)]);
        $user = User::where('id', $userGice->id)->first();
        Auth::login($user, true);

        return redirect('/notifications');
    }

    public function admin()
    {


        User::updateOrCreate(['id' => 5], ['name' => 'admin', 'token' => '456456456456456', 'pri_grp' => 5, 'api_token' => Str::random(60)]);
        $user = User::where('id', 5)->first();
        Auth::login($user, true);

        return redirect('/notifications');
    }

    public function makeUserRole()
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
        // $user->assignRole('Super Admin');
        $role = Role::findByName('Super Admin');

    }

    public function getAllUsersRoles()
    {
        foreach(User::with('roles')->get() as $user){;
            echo $user;
            dd($user);
        }
        // $users= User::with('roles')->get();

        // echo $users;
        // dd($users);

    }



    public function logout()
    {
        Auth::logout();
        return view('auth.login');
    }

    public function login()
    {

        return view('auth.login');
    }

    public function index()
    {
        return ['users' => User::select('id','name')->get()];
    }
}
