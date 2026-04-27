<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\DriverLocation;
use App\Events\DriverLocationUpdated;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /*
    |-----------------------------------------
    | UPDATE DRIVER LOCATION (LIVE GPS)
    |-----------------------------------------
    */
    public function update(Request $request)
    {
        $request->validate([
            'latitude'   => 'required',
            'longitude'  => 'required',
            'speed'      => 'nullable',
            'heading'    => 'nullable',
        ]);

        /*
        |-----------------------------------------
        | AUTHENTICATED DRIVER
        |-----------------------------------------
        */
        $driver = auth('driver')->user();

        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized driver'
            ], 401);
        }

        /*
        |-----------------------------------------
        | UPDATE OR CREATE LOCATION
        |-----------------------------------------
        */
        $location = DriverLocation::updateOrCreate(
            ['driver_id' => $driver->id],
            [
                'latitude'  => $request->latitude,
                'longitude' => $request->longitude,
                'speed'     => $request->speed,
                'heading'   => $request->heading,
            ]
        );

        /*
        |-----------------------------------------
        | REAL-TIME BROADCAST
        |-----------------------------------------
        */
        broadcast(new DriverLocationUpdated([
            'driver_id' => $driver->id,
            'admin_id'  => $driver->admin_id,
            'latitude'  => $location->latitude,
            'longitude' => $location->longitude,
            'speed'     => $location->speed,
            'heading'   => $location->heading,
        ]));

        return response()->json([
            'status' => true,
            'message' => 'Location updated successfully',
            'data' => $location
        ]);
    }
}