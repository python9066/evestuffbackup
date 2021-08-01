<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('notes', function () {
    return Auth::check();
});

Broadcast::channel('campaigns', function () {
    return Auth::check();
});

Broadcast::channel('campaignsystem.{id}', function () {
    return Auth::check();
});

Broadcast::channel('userupdate', function () {
    return Auth::check();
});

Broadcast::channel('campaignsystemmembers.{id}', function () {
    return Auth::check();
});

Broadcast::channel('stations', function () {
    return Auth::check();
});

Broadcast::channel('towers', function () {
    return Auth::check();
});

Broadcast::channel('campaignlogs.{id}', function () {
    return Auth::check();
});

Broadcast::channel('nodemessage.{id}', function () {
    return Auth::check();
});

Broadcast::channel('multicampaigns', function () {
    return Auth::check();
});

Broadcast::channel('stationinfo', function () {
    return Auth::check();
});

Broadcast::channel('stationmessage.{id}', function () {
    return Auth::check();
});

Broadcast::channel('towermessage.{id}', function () {
    return Auth::check();
});

Broadcast::channel('chillstationmessage.{id}', function () {
    return Auth::check();
});

Broadcast::channel('chillstationinfo', function () {
    return Auth::check();
});

Broadcast::channel('chillstations', function () {
    return Auth::check();
});

Broadcast::channel('ammorequest', function () {
    return Auth::check();
});

Broadcast::channel('recon', function () {
    return Auth::check();
});

Broadcast::channel('recontask.{id}', function () {
    return Auth::check();
});









  /////---------------------/////

//  Broadcast::channel('App.User.{id}', function ($user, $id) {
//     return true;
// });

// Broadcast::channel('notes', function () {
//     return true;
//   });

//   Broadcast::channel('campaigns', function () {
//     return true;
//   });

//   Broadcast::channel('campaignsystem.{id}', function () {
//     return true;
//   });

//   Broadcast::channel('userupdate', function () {
//     return true;
//   });

//   Broadcast::channel('campaignsystemmembers.{id}', function () {
//     return true;
//   });

//   Broadcast::channel('stations', function () {
//     return true;
//   });

//   Broadcast::channel('towers', function () {
//     return true;
//   });
