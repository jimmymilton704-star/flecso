<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    /*
    |-----------------------------------------
    | GET DRIVER TRIPS
    |-----------------------------------------
    */
    public function index(Request $request)
    {
        $driver = auth('driver')->user();

        $trips = Trip::where('driver_id', $driver->id)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'data' => $trips
        ]);
    }

    /*
    |-----------------------------------------
    | SINGLE TRIP
    |-----------------------------------------
    */
    public function show(Request $request, $id)
    {
        $driver = auth('driver')->user();

        $trip = Trip::where('id', $id)
            ->where('driver_id', $driver->id)
            ->first();

        if (!$trip) {
            return response()->json([
                'status' => false,
                'message' => 'Trip not found'
            ], 404);
        }
        $tripaccount = $trip->account()->with('transactions')->first();

        return response()->json([
            'status' => true,
            'data' => $trip,
            'account' => $tripaccount
        ]);
    }

    /*
    |-----------------------------------------
    | VERIFY TRUCK (QR SCAN)
    |-----------------------------------------
    */
    public function verifyTruck(Request $request)
    {
        $request->validate([
            'trip_id'  => 'required|exists:trips,id',
            'driver_id'=> 'required',
            'truck_id' => 'required'
        ]);

        $driver = auth('driver')->user();

        $trip = Trip::where('id', $request->trip_id)
            ->where('driver_id', $driver->id)
            ->first();

        if (!$trip || $trip->truck_id != $request->truck_id) {
            return response()->json([
                'status' => false,
                'message' => 'Truck verification failed'
            ], 400);
        }

        //  SAVE verification
        $trip->update([
            'truck_verified' => true
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Truck verified successfully'
        ]);
    }

    /*
    |-----------------------------------------
    | VERIFY CONTAINER (QR SCAN)
    |-----------------------------------------
    */
    public function verifyContainer(Request $request)
    {
        $request->validate([
            'trip_id'      => 'required|exists:trips,id',
            'driver_id'    => 'required',
            'container_id' => 'required'
        ]);

        $driver = auth('driver')->user();

        $trip = Trip::where('id', $request->trip_id)
            ->where('driver_id', $driver->id)
            ->first();

        if (!$trip || $trip->container_id != $request->container_id) {
            return response()->json([
                'status' => false,
                'message' => 'Container verification failed'
            ], 400);
        }

        //  SAVE verification
        $trip->update([
            'container_verified' => true
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Container verified successfully'
        ]);
    }

    /*
    |-----------------------------------------
    | START TRIP
    |-----------------------------------------
    */
    public function startTrip(Request $request)
    {
        $request->validate([
            'trip_id' => 'required|exists:trips,id',
        ]);

        $driver = auth('driver')->user();

        $trip = Trip::where('id', $request->trip_id)
            ->where('driver_id', $driver->id)
            ->first();

        if (!$trip) {
            return response()->json([
                'status' => false,
                'message' => 'Trip not found'
            ], 404);
        }

        //  IMPORTANT CHECK
        if (!$trip->truck_verified || !$trip->container_verified) {
            return response()->json([
                'status' => false,
                'message' => 'Please verify truck and container first'
            ], 403);
        }

        if ($trip->trip_status !== 'pending') {
            return response()->json([
                'status' => false,
                'message' => 'Trip already started or completed'
            ]);
        }

        $trip->update([
            'trip_status' => 'active'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Trip started successfully'
        ]);
    }

    /*
    |-----------------------------------------
    | END TRIP
    |-----------------------------------------
    */
    public function endTrip(Request $request)
    {
        $request->validate([
            'trip_id' => 'required|exists:trips,id',
        ]);

        $driver = auth('driver')->user();
        $trip = Trip::where('id', $request->trip_id)
            ->where('driver_id', $driver->id)
            ->first();

        if (!$trip || $trip->trip_status !== 'active') {
            return response()->json([
                'status' => false,
                'message' => 'Trip not active'
            ]);
        }

        $trip->update([
            'trip_status' => 'completed'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Trip completed successfully'
        ]);
    }

    /*
    |-----------------------------------------
    | CANCEL TRIP
    |-----------------------------------------
    */
    public function cancelTrip(Request $request)
    {
        $request->validate([
            'trip_id' => 'required|exists:trips,id',
        ]);

        $driver = auth('driver')->user();
        $trip = Trip::where('id', $request->trip_id)
            ->where('driver_id', $driver->id)
            ->first();

        if (!$trip) {
            return response()->json([
                'status' => false,
                'message' => 'Trip not found'
            ]);
        }

        if ($trip->trip_status === 'completed') {
            return response()->json([
                'status' => false,
                'message' => 'Cannot cancel completed trip'
            ]);
        }

        $trip->update([
            'trip_status' => 'cancelled'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Trip cancelled successfully'
        ]);
    }
}