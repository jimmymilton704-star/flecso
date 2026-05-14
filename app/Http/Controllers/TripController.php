<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Driver;
use App\Models\TruckHealthLog;
use App\Models\Truck;
use App\Models\TripAccount;
use App\Models\TripAccountTransaction;
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

        $trips = Trip::with([
            'driver:id,full_name',
            'truck',
            'container',
            'account.transactions' => function ($query) {
                $query->latest();
            }
        ])
            ->where('admin_id', $adminId)
            ->latest()
            ->paginate(10);

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
            'drivers' => Driver::where('admin_id', auth()->id())->get(),
            'trucks' => Truck::where('admin_id', auth()->id())->get(),
            'containers' => Container::where('admin_id', auth()->id())->get(),
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

        TripAccount::create([
            'admin_id' => auth()->id(),
            'trip_id' => $trip->id,
            'driver_id' => $request->driver_id,
            'truck_id' => $request->truck_id,
            'opening_amount' => $request->payment_amount ?? 0,
            'remaining_amount' => $request->payment_amount ?? 0,
            'total_expense' => 0,
            'status' => 'active',
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

        $trip = Trip::with([
            'driver.location',
            'truck',
            'container',
            'account.transactions' => function ($query) {
                $query->latest();
            }
        ])
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
            'drivers' => Driver::where('admin_id', $adminId)->get(),
            'trucks' => Truck::where('admin_id', $adminId)->get(),
            'containers' => Container::where('admin_id', $adminId)->get(),
        ]);
    }

    /*
    |-----------------------------------------
    | UPDATE TRIP
    |-----------------------------------------
    */
    public function update(Request $request, $id)
    {
        // dd($request->all());
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

        $trip->update($request->all());

        $tripAccount = TripAccount::where('trip_id', $trip->id)->first();

        if ($tripAccount) {
            $tripAccount->update([
                'opening_amount' => $request->payment_amount ?? 0,
                'remaining_amount' => $request->payment_amount ?? 0,
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


    public function paymentSuggestion(Request $request)
    {
        $adminId = auth()->id();

        $request->validate([
            'pickup_lat' => 'required|numeric',
            'pickup_lng' => 'required|numeric',
            'delivery_lat' => 'required|numeric',
            'delivery_lng' => 'required|numeric',
        ]);

        /*
    |--------------------------------------------------------------------------
    | Bigger tolerance
    |--------------------------------------------------------------------------
    | 0.01 approx 1km. Google location lat/lng same place ke liye thora change
    | kar sakta hai, isliye 0.001 kabhi kabhi match nahi karta.
    */
        $tolerance = 0.01;

        $trips = Trip::with([
            'driver:id,full_name',
            'truck:id,truck_number',
            'container:id,container_license_number',
            'account.transactions' => function ($query) {
                $query->latest();
            },
        ])
            ->where('admin_id', $adminId)
            ->whereNotNull('payment_amount')
            ->whereNotNull('pickup_lat')
            ->whereNotNull('pickup_lng')
            ->whereNotNull('delivery_lat')
            ->whereNotNull('delivery_lng')
            ->whereRaw('ABS(CAST(pickup_lat AS DECIMAL(10,6)) - ?) <= ?', [
                $request->pickup_lat,
                $tolerance
            ])
            ->whereRaw('ABS(CAST(pickup_lng AS DECIMAL(10,6)) - ?) <= ?', [
                $request->pickup_lng,
                $tolerance
            ])
            ->whereRaw('ABS(CAST(delivery_lat AS DECIMAL(10,6)) - ?) <= ?', [
                $request->delivery_lat,
                $tolerance
            ])
            ->whereRaw('ABS(CAST(delivery_lng AS DECIMAL(10,6)) - ?) <= ?', [
                $request->delivery_lng,
                $tolerance
            ])
            ->latest()
            ->limit(5)
            ->get();

        if ($trips->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No previous payment found for this route.',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Previous trip data found for this route.',
            'data' => $trips->map(function ($trip) {

                $account = $trip->account;

                return [
                    'id' => $trip->id,
                    'trip_id' => $trip->trip_id,
                    'trip_type' => $trip->trip_type,
                    'trip_status' => $trip->trip_status,

                    'driver_id' => $trip->driver_id,
                    'driver_name' => optional($trip->driver)->full_name,

                    'truck_id' => $trip->truck_id,
                    'truck_number' => optional($trip->truck)->truck_number,

                    'container_id' => $trip->container_id,
                    'container_number' => optional($trip->container)->container_license_number,

                    'pickup_location' => $trip->pickup_location,
                    'delivery_location' => $trip->delivery_location,
                    'pickup_lat' => $trip->pickup_lat,
                    'pickup_lng' => $trip->pickup_lng,
                    'delivery_lat' => $trip->delivery_lat,
                    'delivery_lng' => $trip->delivery_lng,
                    'distance_km' => $trip->distance_km,
                    'eta_mins' => $trip->eta_mins,

                    'payment_amount' => $trip->payment_amount,

                    'delivery_name' => $trip->delivery_name,
                    'delivery_phone' => $trip->delivery_phone,
                    'delivery_email' => $trip->delivery_email,

                    'package_description' => $trip->package_description,
                    'package_weight' => $trip->package_weight,
                    'package_height' => $trip->package_height,
                    'package_length' => $trip->package_length,
                    'package_width' => $trip->package_width,

                    'created_at' => optional($trip->created_at)->format('d M Y'),

                    /*
                |--------------------------------------------------------------------------
                | Account Details
                |--------------------------------------------------------------------------
                */
                    'account' => $account ? [
                        'id' => $account->id,
                        'opening_amount' => $account->opening_amount,
                        'total_expense' => $account->total_expense,
                        'remaining_amount' => $account->remaining_amount,
                        'status' => $account->status,
                        'created_at' => optional($account->created_at)->format('d M Y, h:i A'),

                        'transactions' => $account->transactions->map(function ($transaction) {
                            return [
                                'id' => $transaction->id,
                                'type' => $transaction->type,
                                'amount' => $transaction->amount,
                                'expense_date' => $transaction->expense_date
                                    ? \Carbon\Carbon::parse($transaction->expense_date)->format('d M Y')
                                    : optional($transaction->created_at)->format('d M Y'),

                                'title' => $transaction->title,
                                'description' => $transaction->description,

                                'source_type' => $transaction->source_type,
                                'source_name' => $transaction->source_name,
                                'source_id' => $transaction->source_id,

                                'balance_before' => $transaction->balance_before,
                                'balance_after' => $transaction->balance_after,
                            ];
                        })->values(),
                    ] : null,
                ];
            })->values(),
        ]);
    }
}
