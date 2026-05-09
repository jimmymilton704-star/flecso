<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    /*
    |-----------------------------------------
    | GET ALL TRIPS
    |-----------------------------------------
    */
    public function index()
    {
        $adminId = auth()->id();

        $trips = Trip::with(['driver:id,full_name', 'truck', 'container'])
            ->where('admin_id', $adminId)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Trips fetched successfully',
            'data' => $trips
        ]);
    }

    /*
    |-----------------------------------------
    | STORE / CREATE TRIP
    |-----------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'trip_id'           => 'required|string',
            'trip_type'         => 'required|string',
            'pickup_location'   => 'required|string',
            'delivery_location' => 'required|string',
            'trip_status'       => 'required|string',

            'pickup_lat'        => 'nullable|numeric|between:-90,90',
            'pickup_lng'        => 'nullable|numeric|between:-180,180',
            'delivery_lat'      => 'nullable|numeric|between:-90,90',
            'delivery_lng'      => 'nullable|numeric|between:-180,180',

            'distance_km'       => 'nullable|numeric',
            'eta_mins'          => 'nullable|integer',
            'schedule_datetime' => 'nullable|date',
            'payment_amount'    => 'nullable|numeric',

            'driver_id'    => 'nullable|exists:drivers,id',
            'truck_id'     => 'nullable|exists:trucks,id',
            'container_id' => 'nullable|exists:containers,id',

            'delivery_name'  => 'nullable|string',
            'delivery_phone' => 'nullable|string',
            'delivery_email' => 'nullable|email',

            'package_description' => 'nullable|string',
            'package_weight'      => 'nullable|numeric',
            'package_height'      => 'nullable|numeric',
            'package_length'      => 'nullable|numeric',
            'package_width'       => 'nullable|numeric',
        ]);

        $adminId = auth()->id();

        $data = $request->all();
        $data['admin_id'] = $adminId;

        $trip = Trip::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Trip created successfully',
            'data' => $trip
        ]);
    }

    /*
    |-----------------------------------------
    | UPDATE TRIP
    |-----------------------------------------
    */
    public function update(Request $request, $id)
    {
        $adminId = auth()->id();

        $trip = Trip::where('id', $id)
            ->where('admin_id', $adminId)
            ->firstOrFail();

        $trip->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Trip updated successfully',
            'data' => $trip
        ]);
    }

    /*
    |-----------------------------------------
    | DELETE TRIP
    |-----------------------------------------
    */
    public function destroy($id)
    {
        $adminId = auth()->id();

        $trip = Trip::where('id', $id)
            ->where('admin_id', $adminId)
            ->firstOrFail();

        $trip->delete();

        return response()->json([
            'status' => true,
            'message' => 'Trip deleted successfully'
        ]);
    }

    /*
    |-----------------------------------------
    | SINGLE TRIP
    |-----------------------------------------
    */
    public function show($id)
    {
        $adminId = auth()->id();

        $trip = Trip::with(['driver:id,full_name', 'truck', 'container'])
            ->where('id', $id)
            ->where('admin_id', $adminId)
            ->firstOrFail();

        return response()->json([
            'status' => true,
            'message' => 'Trip fetched successfully',
            'data' => $trip
        ]);
    }
}