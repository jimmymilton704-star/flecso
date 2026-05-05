<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SosAlertController;
use App\Http\Controllers\TruckController;
use App\Http\Controllers\ContainerController;
use App\Http\Controllers\DriverController;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (GUEST ONLY)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Forgot password
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])
        ->name('password.request');

    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
        ->name('password.email');

    // Reset password
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])
        ->name('password.reset');

    Route::post('/reset-password', [AuthController::class, 'resetPassword'])
        ->name('password.update');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/sos-alerts', [SosAlertController::class, 'index'])->name('sos.index');

    Route::get('/sos-alerts/{id}', [SosAlertController::class, 'show'])->name('sos.show');

    Route::post('/sos-alerts/resolve', [SosAlertController::class, 'resolve'])->name('sos.resolve');

    // Trucks
    Route::prefix('trucks')->name('trucks.')->group(function () {

        Route::get('/', [TruckController::class, 'index'])->name('index');
        Route::get('/create', [TruckController::class, 'create'])->name('create');
        Route::post('/store', [TruckController::class, 'store'])->name('store');

        Route::get('/{id}', [TruckController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [TruckController::class, 'edit'])->name('edit');
        Route::post('/{id}/update', [TruckController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [TruckController::class, 'destroy'])->name('destroy');

        Route::post('/assign-driver', [TruckController::class, 'assignDriver'])->name('assignDriver');
    });

    Route::prefix('containers')->name('containers.')->group(function () {

        Route::get('/', [ContainerController::class, 'index'])->name('index');
        Route::get('/create', [ContainerController::class, 'create'])->name('create');
        Route::post('/store', [ContainerController::class, 'store'])->name('store');

        Route::get('/{id}/show', [ContainerController::class, 'show'])->name('show');

        Route::get('/{id}/edit', [ContainerController::class, 'edit'])->name('edit');
        Route::post('/{id}/update', [ContainerController::class, 'update'])->name('update');

        Route::post('/{id}/delete', [ContainerController::class, 'destroy'])->name('destroy');
    });



    Route::prefix('drivers')->name('drivers.')->group(function () {
        Route::get('/', [DriverController::class, 'index'])->name('index');
        Route::get('/create', [DriverController::class, 'create'])->name('create');
        Route::post('/store', [DriverController::class, 'store'])->name('store');
        Route::get('/show/{id}', [DriverController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [DriverController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [DriverController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [DriverController::class, 'destroy'])->name('delete');
        Route::post('/update-status', [DriverController::class, 'updateStatus'])->name('update.status');
    });

    // Logout
    Route::get('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

/*
|--------------------------------------------------------------------------
| PAYMENT (OPTIONAL)
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
