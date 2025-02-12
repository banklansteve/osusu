<?php

use App\Models\Saving;
use Illuminate\Support\Facades\Log;
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
// Broadcast::routes(['middleware' => ['auth:sanctum']]);

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('test.{id}', function ($user, $id) {
    Log::info("User ID: $user->id, Channel ID: $id");
    return $user->id === (int) $id;
});

// Broadcast::channel('savings.{savings_uid}.creator', function ($user, $savingsUid) {
//     $saving = Saving::where('savings_uid', $savingsUid)->first();

//     // Ensure the saving exists and the authenticated user is the creator.
//     return (int) $saving->creator_id === (int) $user->id;
// });


Broadcast::channel('App.Models.User.{receiverId}', function($user, $receiverId){
    return $user->id === (int) $receiverId;
});
