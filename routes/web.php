<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SosAlertController;
use App\Http\Controllers\TruckController;
use App\Http\Controllers\ContainerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Admin\FuelAnalyticsController;
use App\Http\Controllers\TripTrackingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserManagementController;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware('auth');

Route::get('/delete_account_form', [UserController::class, 'DeleteShow'])
    ->name('delete.account.form');

Route::post('/delete_account_request', [UserController::class, 'requestDeletion'])
    ->name('delete.account.request');

Route::get('/delete_account_confirm', [UserController::class, 'confirmDeletion'])
    ->name('delete.account.confirm')
    ->middleware('signed');

/*
|--------------------------------------------------------------------------
| PUBLIC TRIP TRACKING
|--------------------------------------------------------------------------
*/

Route::get('/track-trip/{token}', [TripTrackingController::class, 'show'])
    ->name('trip.track');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/login/phone', [AuthController::class, 'loginWithPhone'])->name('login.phone');

    Route::get('/verify-otp', [AuthController::class, 'showOtpForm'])->name('otp.verify.form');
    Route::post('/otp/verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');
    Route::post('/otp/resend', [AuthController::class, 'resendOtp'])->name('otp.resend');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])
        ->name('password.request');

    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])
        ->name('password.reset');

    Route::post('/reset-password', [AuthController::class, 'resetPassword'])
        ->name('password.update');
});

/*
|--------------------------------------------------------------------------
| PROFILE COMPLETION
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('complete-profile')->name('profile.')->group(function () {

    Route::view('/step-1', 'profile-steps.step1', ['step' => 1])->name('step1');
    Route::view('/step-2', 'profile-steps.step2', ['step' => 2])->name('step2');
    Route::view('/step-3', 'profile-steps.step3', ['step' => 3])->name('step3');
    Route::view('/step-4', 'profile-steps.step4', ['step' => 4])->name('step4');

    Route::post('/step-1', [AuthController::class, 'completeProfileStep1'])->name('step1.post');
    Route::post('/step-2', [AuthController::class, 'completeProfileStep2'])->name('step2.post');
    Route::post('/step-3', [AuthController::class, 'completeProfileStep3'])->name('step3.post');
    Route::post('/step-4', [AuthController::class, 'completeProfileStep4'])->name('step4.post');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'profile.completed',
    'activity.log'
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('admin_or_permission:dashboard_access');

    Route::get('/dashboard/leaderboard', [DashboardController::class, 'leaderboard'])
        ->name('leaderboard')
        ->middleware('admin_or_permission:leaderboard_view');

    /*
    |--------------------------------------------------------------------------
    | SOS ALERTS
    |--------------------------------------------------------------------------
    */

    Route::get('/sos-alerts', [SosAlertController::class, 'index'])
        ->name('sos.index')
        ->middleware('admin_or_permission:sos_list|sos_view|sos_update');

    Route::get('/sos-alerts/{id}', [SosAlertController::class, 'show'])
        ->name('sos.show')
        ->middleware('admin_or_permission:sos_view');

    Route::get('/fuel-alerts/{id}', [SosAlertController::class, 'Fuelshow'])
        ->name('fuel.show')
        ->middleware('admin_or_permission:sos_view|fuel_alert_view');

    Route::get('/fleet-alerts/{id}', [SosAlertController::class, 'Fleetshow'])
        ->name('fleet.show')
        ->middleware('admin_or_permission:sos_view|fleet_alert_view');

    Route::post('/sos-alerts/resolve', [SosAlertController::class, 'resolve'])
        ->name('sos.resolve')
        ->middleware('admin_or_permission:sos_update');

    /*
    |--------------------------------------------------------------------------
    | TRUCKS
    |--------------------------------------------------------------------------
    */

    Route::prefix('trucks')->name('trucks.')->group(function () {

        Route::get('/', [TruckController::class, 'index'])
            ->name('index')
            ->middleware('admin_or_permission:truck_list|truck_view|truck_create|truck_update|truck_delete');

        Route::get('/create', [TruckController::class, 'create'])
            ->name('create')
            ->middleware('admin_or_permission:truck_create');

        Route::post('/store', [TruckController::class, 'store'])
            ->name('store')
            ->middleware('admin_or_permission:truck_create');

        Route::get('/{id}', [TruckController::class, 'show'])
            ->name('show')
            ->middleware('admin_or_permission:truck_view');

        Route::get('/{id}/edit', [TruckController::class, 'edit'])
            ->name('edit')
            ->middleware('admin_or_permission:truck_update');

        Route::post('/{id}/update', [TruckController::class, 'update'])
            ->name('update')
            ->middleware('admin_or_permission:truck_update');

        Route::delete('/{id}/delete', [TruckController::class, 'destroy'])
            ->name('destroy')
            ->middleware('admin_or_permission:truck_delete');

        Route::post('/assign-driver', [TruckController::class, 'assignDriver'])
            ->name('assignDriver')
            ->middleware('admin_or_permission:truck_update');

        Route::post('/import', [TruckController::class, 'import'])
            ->name('import')
            ->middleware('admin_or_permission:truck_create');
    });

    /*
    |--------------------------------------------------------------------------
    | CONTAINERS
    |--------------------------------------------------------------------------
    */

    Route::prefix('containers')->name('containers.')->group(function () {

        Route::get('/', [ContainerController::class, 'index'])
            ->name('index')
            ->middleware('admin_or_permission:container_list|container_view|container_create|container_update|container_delete');

        Route::get('/create', [ContainerController::class, 'create'])
            ->name('create')
            ->middleware('admin_or_permission:container_create');

        Route::post('/store', [ContainerController::class, 'store'])
            ->name('store')
            ->middleware('admin_or_permission:container_create');

        Route::get('/{id}/show', [ContainerController::class, 'show'])
            ->name('show')
            ->middleware('admin_or_permission:container_view');

        Route::get('/{id}/edit', [ContainerController::class, 'edit'])
            ->name('edit')
            ->middleware('admin_or_permission:container_update');

        Route::post('/{id}/update', [ContainerController::class, 'update'])
            ->name('update')
            ->middleware('admin_or_permission:container_update');

        Route::post('/{id}/delete', [ContainerController::class, 'destroy'])
            ->name('destroy')
            ->middleware('admin_or_permission:container_delete');

        Route::post('/import', [ContainerController::class, 'import'])
            ->name('import')
            ->middleware('admin_or_permission:container_create');
    });

    /*
    |--------------------------------------------------------------------------
    | DRIVERS
    |--------------------------------------------------------------------------
    */

    Route::prefix('drivers')->name('drivers.')->group(function () {

        Route::get('/', [DriverController::class, 'index'])
            ->name('index')
            ->middleware('admin_or_permission:driver_list|driver_view|driver_create|driver_update|driver_delete');

        Route::get('/create', [DriverController::class, 'create'])
            ->name('create')
            ->middleware('admin_or_permission:driver_create');

        Route::post('/store', [DriverController::class, 'store'])
            ->name('store')
            ->middleware('admin_or_permission:driver_create');

        Route::get('/show/{id}', [DriverController::class, 'show'])
            ->name('show')
            ->middleware('admin_or_permission:driver_view');

        Route::get('/edit/{id}', [DriverController::class, 'edit'])
            ->name('edit')
            ->middleware('admin_or_permission:driver_update');

        Route::post('/update/{id}', [DriverController::class, 'update'])
            ->name('update')
            ->middleware('admin_or_permission:driver_update');

        Route::delete('/delete/{id}', [DriverController::class, 'destroy'])
            ->name('delete')
            ->middleware('admin_or_permission:driver_delete');

        Route::post('/update-status', [DriverController::class, 'updateStatus'])
            ->name('update.status')
            ->middleware('admin_or_permission:driver_update');

        Route::post('/import', [DriverController::class, 'import'])
            ->name('import')
            ->middleware('admin_or_permission:driver_create');
    });

    /*
    |--------------------------------------------------------------------------
    | TRIPS
    |--------------------------------------------------------------------------
    */

    Route::prefix('trips')->name('trips.')->group(function () {

        Route::get('/', [TripController::class, 'index'])
            ->name('index')
            ->middleware('admin_or_permission:trip_list|trip_view|trip_create|trip_update|trip_delete');

        Route::get('/create', [TripController::class, 'create'])
            ->name('create')
            ->middleware('admin_or_permission:trip_create');

        Route::post('/store', [TripController::class, 'store'])
            ->name('store')
            ->middleware('admin_or_permission:trip_create');

        Route::get('/{id}/show', [TripController::class, 'show'])
            ->name('show')
            ->middleware('admin_or_permission:trip_view');

        Route::get('/{id}/edit', [TripController::class, 'edit'])
            ->name('edit')
            ->middleware('admin_or_permission:trip_update');

        Route::post('/{id}/update', [TripController::class, 'update'])
            ->name('update')
            ->middleware('admin_or_permission:trip_update');

        Route::post('/{id}/delete', [TripController::class, 'destroy'])
            ->name('destroy')
            ->middleware('admin_or_permission:trip_delete');
    });

    Route::get('/trip-payment-suggestion', [TripController::class, 'paymentSuggestion'])
        ->name('trips.payment-suggestion')
        ->middleware('admin_or_permission:trip_create|trip_update');

    /*
    |--------------------------------------------------------------------------
    | USERS PROFILE / COMPANY
    |--------------------------------------------------------------------------
    */

    Route::prefix('users')->name('users.')->group(function () {

        Route::post('/company/store', [UserController::class, 'companystore'])
            ->name('company.store')
            ->middleware('admin_or_permission:setting_update');

        Route::post('/profile/update', [UserController::class, 'profileUpdate'])
            ->name('profile.update')
            ->middleware('admin_or_permission:setting_update');

        Route::get('/{id}/show', [UserController::class, 'show'])
            ->name('show')
            ->middleware('admin_or_permission:user_view');

        Route::get('/{id}/edit', [UserController::class, 'edit'])
            ->name('edit')
            ->middleware('admin_or_permission:user_update');

        Route::post('/{id}/update', [UserController::class, 'update'])
            ->name('update')
            ->middleware('admin_or_permission:user_update');

        Route::post('/{id}/delete', [UserController::class, 'destroy'])
            ->name('destroy')
            ->middleware('admin_or_permission:user_delete');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN ROLE, PERMISSION, USER MANAGEMENT
    |--------------------------------------------------------------------------
    | Only admin role can access these.
    |--------------------------------------------------------------------------
    */

    Route::prefix('admin')->name('admin.')->group(function () {

        Route::resource('permissions', PermissionController::class)
            ->except(['show']);

        Route::resource('roles', RoleController::class)
            ->except(['show']);

        Route::get('roles/{role}/permissions', [RoleController::class, 'permissions'])
            ->name('roles.permissions');

        Route::post('roles/{role}/permissions', [RoleController::class, 'syncPermissions'])
            ->name('roles.permissions.sync');

        Route::resource('users-management', UserManagementController::class)
            ->except(['show']);
    });

    /*
    |--------------------------------------------------------------------------
    | CHAT
    |--------------------------------------------------------------------------
    */

    Route::prefix('chat')->name('chat.')->group(function () {

        Route::get('/', [ChatController::class, 'index'])
            ->name('index')
            ->middleware('admin_or_permission:chat_list|chat_view|chat_send');

        Route::get('/list', [ChatController::class, 'chatList'])
            ->name('list')
            ->middleware('admin_or_permission:chat_list|chat_view');

        Route::post('/create', [ChatController::class, 'createOrGetChat'])
            ->name('create')
            ->middleware('admin_or_permission:chat_send');

        Route::get('/messages', [ChatController::class, 'messages'])
            ->name('messages')
            ->middleware('admin_or_permission:chat_view');

        Route::post('/send', [ChatController::class, 'sendMessage'])
            ->name('send')
            ->middleware('admin_or_permission:chat_send');

        Route::post('/seen', [ChatController::class, 'markAsSeen'])
            ->name('seen')
            ->middleware('admin_or_permission:chat_view');

        Route::post('/create-or-get', [ChatController::class, 'createOrGetChat'])
            ->name('create_or_get')
            ->middleware('admin_or_permission:chat_send');

        Route::get('/drivers', [ChatController::class, 'drivers'])
            ->name('drivers')
            ->middleware('admin_or_permission:chat_list|chat_send');

        Route::post('/broadcast', [ChatController::class, 'broadcast'])
            ->name('broadcast')
            ->middleware('admin_or_permission:chat_send');
    });

    /*
    |--------------------------------------------------------------------------
    | SYSTEM
    |--------------------------------------------------------------------------
    */

    Route::get('/setting', function () {
        return view('settings.setting');
    })->name('setting')
        ->middleware('admin_or_permission:setting_view|setting_update');

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])
        ->name('activity.logs')
        ->middleware('admin_or_permission:activity_log_list|activity_log_view');

    Route::get('/fuel/dashboard', [FuelAnalyticsController::class, 'dashboard'])
        ->name('fuel.dashboard')
        ->middleware('admin_or_permission:fuel_dashboard_view');

    Route::get('/fuel/alerts', [FuelAnalyticsController::class, 'alerts'])
        ->name('fuel.alerts')
        ->middleware('admin_or_permission:fuel_alert_list|fuel_alert_view');

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    Route::get('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

/*
|--------------------------------------------------------------------------
| PAYMENT OPTIONAL
|--------------------------------------------------------------------------
*/

Route::get('/payment-success', function () {
    return response()->json([
        'status' => true,
        'message' => 'Payment successful. Subscription will activate shortly.'
    ]);
});

Route::get('/payment-cancel', function () {
    return response()->json([
        'status' => false,
        'message' => 'Payment cancelled'
    ]);
});