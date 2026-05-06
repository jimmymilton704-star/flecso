<?php

use App\Helpers\AppHelper;
use Illuminate\Support\Facades\Auth;
use App\Models\User;



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