<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Chat;
use App\Models\Trip;

/*
|--------------------------------------------------------------------------
| CHAT CHANNEL (ADMIN + DRIVER)
|--------------------------------------------------------------------------
*/

Broadcast::channel('chat.{chatId}', function ($user = null, $chatId) {

    // 🔹 Try ADMIN (users table)
    if (auth()->check()) {
        return Chat::where('id', $chatId)
            ->where(function ($q) {
                $q->where('admin_id', auth()->id());
            })
            ->exists();
    }

    // 🔹 Try DRIVER (drivers table)
    if (auth('driver')->check()) {
        return Chat::where('id', $chatId)
            ->where('driver_id', auth('driver')->id())
            ->exists();
    }

    return false;
});


/*
|--------------------------------------------------------------------------
| TRIP CHANNEL (ADMIN ONLY)
|--------------------------------------------------------------------------
*/

Broadcast::channel('trip.{tripId}', function ($user = null, $tripId) {

    if (!auth()->check()) {
        return false;
    }

    return Trip::where('id', $tripId)
        ->where('admin_id', auth()->id())
        ->exists();
});