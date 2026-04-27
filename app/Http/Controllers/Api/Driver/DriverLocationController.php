<?php

namespace App\Http\Controllers\Api\Driver;

use App\Events\DriverLocationUpdated;
use App\Http\Controllers\Controller;
use App\Models\DriverLocation;
use App\Models\Trip;
use Illuminate\Http\Request;

class DriverLocationController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'trip_id'   => 'required|exists:trips,id',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $driver = auth('driver')->user();

        $trip = Trip::where('id', $request->trip_id)
            ->where('status', 'active')
            ->first();

        if (!$trip) {
            return response()->json([
                'status' => false,
                'message' => 'No active trip'
            ], 403);
        }

        $location = DriverLocation::create(
            [
                'driver_id' => $driver->id,
                'trip_id'   => $trip->id,
                'truck_id'  => $trip->truck_id,
                'latitude'  => $request->latitude,
                'longitude' => $request->longitude,
                'recorded_at' => now(),
            ]
        );

        broadcast(new DriverLocationUpdated($location));

        $driver->update(['last_seen' => now()]);

        return response()->json([
            'status' => true,
            'server_time' => now()->toDateTimeString()
        ]);
    }
}
