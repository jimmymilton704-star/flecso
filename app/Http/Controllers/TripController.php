<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Driver;
use App\Models\TruckHealthLog;
use App\Models\Truck;
use App\Models\Container;
use Illuminate\Http\Request;

class TripController extends Controller
{
    /*
    |-----------------------------------------
    | LIST TRIPS (WEB)
    |-----------------------------------------
    */
    public function index()
    {
        $adminId = auth()->id();

        $trips = Trip::with(['driver:id,full_name', 'truck', 'container'])
            ->where('admin_id', $adminId)
            ->latest()
            ->paginate(10); // 👈 instead of get()
        return view('trips.index', compact('trips'));
    }

    /*
    |-----------------------------------------
    | SHOW CREATE FORM
    |-----------------------------------------
    */
    public function create()
    {
        return view('trips.create', [
            'drivers' => Driver::all(),
            'trucks' => Truck::all(),
            'containers' => Container::all(),
        ]);
    }

    /*
    |-----------------------------------------
    | STORE TRIP
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

        $trip = Trip::create([
            ...$request->all(),
            'admin_id' => auth()->id()
        ]);

        $latestHealth = TruckHealthLog::where('truck_id', $trip->truck_id)
            ->latest('recorded_at')
            ->first();

        $previousKm = $latestHealth?->current_km ?? 0;

        $newKm = $previousKm + ($trip->distance_km ?? 0);

        TruckHealthLog::create([
            'truck_id' => $trip->truck_id,
            'current_km' => $newKm,
            'recorded_at' => now(),
        ]);

        return redirect()
            ->route('trips.index')
            ->with('success', 'Trip created successfully');
    }

    /*
    |-----------------------------------------
    | SHOW SINGLE TRIP
    |-----------------------------------------
    */
    public function show($id)
    {
        $adminId = auth()->id();

        $trip = Trip::with(['driver.location', 'truck', 'container'])
            ->where('id', $id)
            ->where('admin_id', $adminId)
            ->firstOrFail();
        return view('trips.show', compact('trip'));
    }

    /*
    |-----------------------------------------
    | EDIT FORM
    |-----------------------------------------
    */
    public function edit($id)
    {
        $adminId = auth()->id();

        $trip = Trip::where('id', $id)
            ->where('admin_id', $adminId)
            ->firstOrFail();

        return view('trips.edit', [
            'trip' => $trip,
            'drivers' => Driver::all(),
            'trucks' => Truck::all(),
            'containers' => Container::all(),
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

        $healthLog = TruckHealthLog::where('truck_id', $trip->truck_id)
            ->latest('recorded_at')
            ->first();

        if ($healthLog) {

            $healthLog->update([
                'current_km' => $healthLog->current_km + ($request->distance_km ?? 0),
                'recorded_at' => now(),
            ]);
        } else {

            TruckHealthLog::create([
                'truck_id' => $trip->truck_id,
                'current_km' => $request->distance_km ?? 0,
                'recorded_at' => now(),
            ]);
        }

        return redirect()
            ->route('trips.index')
            ->with('success', 'Trip updated successfully');
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

        return redirect()
            ->route('trips.index')
            ->with('success', 'Trip deleted successfully');
    }
}
