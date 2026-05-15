<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContainerController;
use App\Http\Controllers\Api\Driver\DashboardController as DriverDashboardController;
use App\Http\Controllers\Api\Driver\LocationController;
use App\Http\Controllers\Api\Driver\TripController as DriverTripController;
use App\Http\Controllers\Api\Driver\SosAlertController as DriverSosAlertController;
use App\Http\Controllers\Api\Driver\ProfileController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Api\TruckController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\SosAlertController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\StripeWebhookController;
use App\Http\Controllers\Api\LiveTrackingController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\Driver\FuelLogController;
use App\Http\Controllers\Api\TripAccountController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/driver-login', [AuthController::class, 'driverLogin']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

Route::get('/test-broadcast', function () {
    event(new \App\Events\TestEvent('Hello from Laravel 🚀'));
    return 'Event Sent!';
});



Broadcast::routes(['middleware' => ['auth:sanctum']]);
Broadcast::routes(['middleware' => ['auth:driver']]);

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (users table)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Profile Steps
    Route::prefix('complete-profile')->group(function () {
        Route::post('/step1', [AuthController::class, 'completeProfileStep1']);
        Route::post('/step2', [AuthController::class, 'completeProfileStep2']);
        Route::post('/step3', [AuthController::class, 'completeProfileStep3']);
        Route::post('/step4', [AuthController::class, 'completeProfileStep4']);
    });

    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile/update', [AuthController::class, 'updateFullProfile']);

    // Drivers
    Route::post('/driver/register', [AuthController::class, 'registerDriver']);

    Route::prefix('drivers')->group(function () {
        Route::get('/', [DriverController::class, 'index']);
        Route::post('/store', [DriverController::class, 'store']);
        Route::post('/update/{id}', [DriverController::class, 'update']);
        Route::delete('/delete/{id}', [DriverController::class, 'destroy']);
        Route::get('/show/{id}', [DriverController::class, 'show']);
        Route::post('/update-status', [DriverController::class, 'updateStatus']);
        Route::post('/import', [DriverController::class, 'import']);
    });

    // Trucks
    Route::prefix('trucks')->group(function () {
        Route::get('/', [TruckController::class, 'index']);
        Route::post('/store', [TruckController::class, 'store']);
        Route::post('/update/{id}', [TruckController::class, 'update']);
        Route::delete('/delete/{id}', [TruckController::class, 'destroy']);
        Route::post('/assign-driver', [TruckController::class, 'assignDriver']);
        Route::get('/show/{id}', [TruckController::class, 'show']);
        Route::post('/import', [TruckController::class, 'import']);
    });

    // Containers
    Route::prefix('containers')->group(function () {
        Route::get('/', [ContainerController::class, 'index']);
        Route::post('/store', [ContainerController::class, 'store']);
        Route::post('/update/{id}', [ContainerController::class, 'update']);
        Route::delete('/delete/{id}', [ContainerController::class, 'destroy']);
        Route::get('/show/{id}', [ContainerController::class, 'show']);
        Route::post('/import', [ContainerController::class, 'import']);
    });

    // Trips
    Route::prefix('trips')->group(function () {
        Route::get('/', [TripController::class, 'index']);
        Route::post('/store', [TripController::class, 'store']);
        Route::post('/update/{id}', [TripController::class, 'update']);
        Route::delete('/delete/{id}', [TripController::class, 'destroy']);
        Route::get('/show/{id}', [TripController::class, 'show']);
        Route::get('/payment-suggestion', [TripController::class, 'paymentSuggestion']);
    });
    
    // Subscription
    Route::get('/plans', [PlanController::class, 'index']);
    Route::get('/subscription', [SubscriptionController::class, 'index']);
    Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
    Route::post('/subscription/cancel-subscription', [SubscriptionController::class, 'cancel']);
    Route::post('/subscription/change-plan', [SubscriptionController::class, 'changePlan']);
    Route::get('/subscription/subscription-status', [SubscriptionController::class, 'getActiveSubscription']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/leaderboard', [DashboardController::class, 'leaderboardApi'])->name('leaderboard');

    // SOS
    Route::get('/sos', [SosAlertController::class, 'index']);
    Route::get('/sos/{id}', [SosAlertController::class, 'show']);
    Route::get('/fuel/{id}', [SosAlertController::class, 'fuelshowApi']);
    Route::get('/fleet/{id}', [SosAlertController::class, 'fleetshowApi']);
    Route::post('/sos/resolve', [SosAlertController::class, 'resolve']);

    // Live Tracking
    Route::get('/live-drivers', [LiveTrackingController::class, 'liveDrivers']);

    
});



/*
|--------------------------------------------------------------------------
| DRIVER ROUTES (drivers table)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:driver')->prefix('driver')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'meDriver']);

    Route::get('/dashboard', [DriverDashboardController::class, 'index']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
    Route::delete('/profile/delete', [ProfileController::class, 'delete']);

    // Trips
    Route::get('/trips', [DriverTripController::class, 'index']);
    Route::get('/trip/{id}', [DriverTripController::class, 'show']);

    Route::post('/verify-truck', [DriverTripController::class, 'verifyTruck']);
    Route::post('/verify-container', [DriverTripController::class, 'verifyContainer']);

    Route::post('/start-trip', [DriverTripController::class, 'startTrip']);
    Route::post('/end-trip', [DriverTripController::class, 'endTrip']);
    Route::post('/cancel-trip', [DriverTripController::class, 'cancelTrip']);

    // SOS
    Route::post('/sos', [DriverSosAlertController::class, 'store']);
    Route::get('/sos-history', [DriverSosAlertController::class, 'driverHistory']);

    // Location
    Route::post('/location', [LocationController::class, 'update']);

    // Fuel Log
    Route::post('/fuel-logs', [FuelLogController::class, 'store']);
    Route::get('/trip/{trip}/fuel-logs', [FuelLogController::class, 'tripFuelLogs']);

    /*
    |--------------------------------------------------------------------------
    | TRIP ACCOUNTS
    |--------------------------------------------------------------------------
    */
    Route::get('/trip-accounts', [TripAccountController::class, 'index']);
    Route::post('/trip-accounts', [TripAccountController::class, 'store']);

    Route::get('/trips/{tripId}/account', [TripAccountController::class, 'show']);
    Route::post('/trips/{tripId}/account/add-balance', [TripAccountController::class, 'addBalance']);

    /*
    |--------------------------------------------------------------------------
    | GENERIC TRIP EXPENSES
    |--------------------------------------------------------------------------
    */
    Route::post('/trip-expenses', [TripAccountController::class, 'addExpense']);

    Route::get('/trips/{tripId}/transactions', [TripAccountController::class, 'transactions']);
    Route::delete('/trip-transactions/{transactionId}', [TripAccountController::class, 'destroyTransaction']);
});



/*
|--------------------------------------------------------------------------
| CHAT (COMMON - BOTH)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum,driver'])->prefix('chat')->group(function () {
    Route::post('/create', [ChatController::class, 'createOrGetChat']);
    Route::post('/send', [ChatController::class, 'sendMessage']);
    Route::get('/messages', [ChatController::class, 'messages']);
    Route::post('/seen', [ChatController::class, 'markAsSeen']);
    Route::get('/list', [ChatController::class, 'chatList']); 
});