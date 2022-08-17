<?php

namespace App\Http\Controllers;


use App\Models\Auth as ModelsAuth;
use Socialite;

class ESITokensController extends Controller
{
    /**
     * ESIController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Redirect the user to the Eve Online authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('eveonline')
            ->scopes([
                'esi-search.search_structures.v1',
                'esi-universe.read_structures.v1',
                'esi-markets.structure_markets.v1',
                'esi-corporations.read_structures.v1',
                'esi-characters.read_notifications.v1',
                'esi-alliances.read_contacts.v1',
            ])
            ->redirect();
    }

    /**
     * Obtain the user information frodwdwwdwm Eve Online.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('eveonline')->user();


        $esi = new ModelsAuth();
        $esi->character_id = $user->id;
        $esi->name = $user->name;
        $esi->token = $user->token;
        $esi->refresh_token = $user->refreshToken;


        $esi->save();
    }
}
