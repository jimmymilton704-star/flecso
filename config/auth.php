<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |------------------------------------------------------------------
    | GUARDS
    |------------------------------------------------------------------
    */
    'guards' => [

        // Admin (users table)
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'sanctum' => [
            'driver' => 'sanctum',
            'provider' => 'users',
        ],

        // 👇 DRIVER GUARD (IMPORTANT)
        'driver' => [
            'driver' => 'sanctum',
            'provider' => 'drivers',
        ],
    ],

    /*
    |------------------------------------------------------------------
    | PROVIDERS
    |------------------------------------------------------------------
    */
    'providers' => [

        // Admins
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // 👇 DRIVERS (IMPORTANT)
        'drivers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Driver::class,
        ],
    ],

    /*
    |------------------------------------------------------------------
    | PASSWORDS
    |------------------------------------------------------------------
    */
    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
        ],
    ],

];