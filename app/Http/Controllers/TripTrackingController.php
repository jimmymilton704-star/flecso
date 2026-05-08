<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\DriverLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TripTrackingController extends Controller
{
    public function show($token)
    {
        try {

            $tripId = decrypt(urldecode($token));
            $trip = Trip::with([
                'driver',
                'truck',
                'container'
            ])->findOrFail($tripId);

            $driverLocation = DriverLocation::where('driver_id', $trip->driver_id)
                ->latest()
                ->first();

            return view('tracking.show', compact(
                'trip',
                'driverLocation'
            ));

        } catch (\Exception $e) {

            abort(404);
        }
    }
}