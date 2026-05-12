<?php

use App\Helpers\AppHelper;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\FuelAlert;



if (!function_exists('user')) {
    function user(): ?User
    {
        return Auth::user();
    }
}

if (!function_exists('userWithRelations')) {
    function userWithRelations(array $relations = []): ?User
    {
        $user = Auth::user();

        if (!$user) {
            return null;
        }

        return User::with($relations)->find($user->id);
    }
}

if (!function_exists('adminAlerts')) {
    function adminAlerts()
    {
        // Check if user logged in
        if (!Auth::check()) {
            return collect(); // empty collection
        }

        $admin = Auth::user();

        return FuelAlert::whereHas('driver', function ($q) use ($admin) {
                $q->where('admin_id', $admin->id);
            })
            ->where('is_resolved', 0)
            ->latest()
            ->get();
    }
}
