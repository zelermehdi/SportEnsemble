<?php


use App\Models\EvenementSportif;
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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('chat-evenement.{evenementId}', function ($user, $evenementId) {
    return \App\Models\EvenementSportif::find($evenementId) !== null;
});

Broadcast::channel('presence-chat-evenement.{id}', function ($user, $id) {
    Log::info("Canal presence", ['user' => $user->id, 'evt' => $id]);
    return ['id' => $user->id, 'name' => $user->name];
});


Broadcast::channel('notifications.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
