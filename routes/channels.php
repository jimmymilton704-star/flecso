<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Chat;
use App\Models\Trip;

/*
|--------------------------------------------------------------------------
| CHAT CHANNEL (ADMIN + DRIVER)
|--------------------------------------------------------------------------
*/

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {

    if (!$user) return false;

    return \App\Models\Chat::where('id', $chatId)
        ->where(function ($q) use ($user) {
            $q->where('admin_id', $user->id)
              ->orWhere('driver_id', $user->id);
        })
        ->exists();
});




/*
|--------------------------------------------------------------------------
| TRIP CHANNEL (ADMIN ONLY)
|--------------------------------------------------------------------------
*/

Broadcast::channel('trip.{tripId}', function ($user = null, $tripId) {

    return $user && $user->id === Trip::find($tripId)?->admin_id;

    return Trip::where('id', $tripId)
        ->where('admin_id', auth()->id())
        ->exists();
});

Broadcast::channel('admin.{id}', function ($user, $id) {
    return $user && (int) $user->id === (int) $id;
});

