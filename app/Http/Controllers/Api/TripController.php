<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\TruckHealthLog;
use App\Models\TripAccount;
use Illuminate\Http\Request;

class TripController extends Controller
{
    /*
    |-----------------------------------------
    | GET ALL TRIPS
    |-----------------------------------------
    */
    public function index(Request $request)
    {
        $adminId = auth()->id();

        $perPage = $request->per_page ?? 10;

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
            ->paginate($perPage);

        /*
        |--------------------------------------------------------------------------
        | All status counts, not according to pagination
        |--------------------------------------------------------------------------
        */
        $tripStats = [
            'total'      => Trip::where('admin_id', $adminId)->count(),
            'active'     => Trip::where('admin_id', $adminId)->where('trip_status', 'active')->count(),
            'in_transit' => Trip::where('admin_id', $adminId)->where('trip_status', 'in_transit')->count(),
            'pending'    => Trip::where('admin_id', $adminId)->where('trip_status', 'pending')->count(),
            'completed'  => Trip::where('admin_id', $adminId)->where('trip_status', 'completed')->count(),
            'cancelled'  => Trip::where('admin_id', $adminId)->where('trip_status', 'cancelled')->count(),
        ];

        return response()->json([
            'status'  => true,
            'message' => 'Trips fetched successfully',
            'stats'   => $tripStats,
            'data'    => $trips
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

        $trip = Trip::create([
            ...$request->all(),
            'admin_id' => $adminId
        ]);

        /*
        |--------------------------------------------------------------------------
        | Truck Health Log
        |--------------------------------------------------------------------------
        */
        if ($trip->truck_id) {
            $latestHealth = TruckHealthLog::where('truck_id', $trip->truck_id)
                ->latest('recorded_at')
                ->first();

            $previousKm = $latestHealth?->current_km ?? 0;
            $newKm = $previousKm + ($trip->distance_km ?? 0);

            TruckHealthLog::create([
                'truck_id'    => $trip->truck_id,
                'current_km'  => $newKm,
                'recorded_at' => now(),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Create Trip Account
        |--------------------------------------------------------------------------
        */
        TripAccount::create([
            'admin_id'          => $adminId,
            'trip_id'           => $trip->id,
            'driver_id'         => $request->driver_id,
            'truck_id'          => $request->truck_id,
            'opening_amount'    => $request->payment_amount ?? 0,
            'remaining_amount'  => $request->payment_amount ?? 0,
            'total_expense'     => 0,
            'status'            => 'active',
        ]);

        $trip->load([
            'driver:id,full_name',
            'truck',
            'container',
            'account.transactions'
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Trip created successfully',
            'data'    => $trip
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

        return response()->json([
            'status'  => true,
            'message' => 'Trip fetched successfully',
            'data'    => $trip
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

        $request->validate([
            'trip_id'           => 'nullable|string',
            'trip_type'         => 'nullable|string',
            'pickup_location'   => 'nullable|string',
            'delivery_location' => 'nullable|string',
            'trip_status'       => 'nullable|string',

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

        /*
        |--------------------------------------------------------------------------
        | Truck Health Log
        |--------------------------------------------------------------------------
        */
        $truckId = $request->truck_id ?? $trip->truck_id;

        if ($truckId) {
            $healthLog = TruckHealthLog::where('truck_id', $truckId)
                ->latest('recorded_at')
                ->first();

            if ($healthLog) {
                $healthLog->update([
                    'current_km'  => $healthLog->current_km + ($request->distance_km ?? 0),
                    'recorded_at' => now(),
                ]);
            } else {
                TruckHealthLog::create([
                    'truck_id'    => $truckId,
                    'current_km'  => $request->distance_km ?? 0,
                    'recorded_at' => now(),
                ]);
            }
        }

        $trip->update($request->all());

        /*
        |--------------------------------------------------------------------------
        | Update Trip Account
        |--------------------------------------------------------------------------
        */
        $tripAccount = TripAccount::where('trip_id', $trip->id)->first();

        if ($tripAccount) {
            $tripAccount->update([
                'driver_id'        => $request->driver_id ?? $tripAccount->driver_id,
                'truck_id'         => $request->truck_id ?? $tripAccount->truck_id,
                'opening_amount'   => $request->payment_amount ?? $tripAccount->opening_amount,
                'remaining_amount' => $request->payment_amount ?? $tripAccount->remaining_amount,
            ]);
        }

        $trip->load([
            'driver:id,full_name',
            'truck',
            'container',
            'account.transactions'
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Trip updated successfully',
            'data'    => $trip
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
            'status'  => true,
            'message' => 'Trip deleted successfully'
        ]);
    }

    /*
    |-----------------------------------------
    | PAYMENT SUGGESTION
    |-----------------------------------------
    */
    public function paymentSuggestion(Request $request)
    {
        $adminId = auth()->id();

        $request->validate([
            'pickup_lat'   => 'required|numeric',
            'pickup_lng'   => 'required|numeric',
            'delivery_lat' => 'required|numeric',
            'delivery_lng' => 'required|numeric',
        ]);

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
                'status'  => false,
                'message' => 'No previous payment found for this route.',
            ]);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Previous trip data found for this route.',
            'data'    => $trips->map(function ($trip) {

                $account = $trip->account;

                return [
                    'id'          => $trip->id,
                    'trip_id'     => $trip->trip_id,
                    'trip_type'   => $trip->trip_type,
                    'trip_status' => $trip->trip_status,

                    'driver_id'   => $trip->driver_id,
                    'driver_name' => optional($trip->driver)->full_name,

                    'truck_id'     => $trip->truck_id,
                    'truck_number' => optional($trip->truck)->truck_number,

                    'container_id'     => $trip->container_id,
                    'container_number' => optional($trip->container)->container_license_number,

                    'pickup_location'   => $trip->pickup_location,
                    'delivery_location' => $trip->delivery_location,
                    'pickup_lat'        => $trip->pickup_lat,
                    'pickup_lng'        => $trip->pickup_lng,
                    'delivery_lat'      => $trip->delivery_lat,
                    'delivery_lng'      => $trip->delivery_lng,
                    'distance_km'       => $trip->distance_km,
                    'eta_mins'          => $trip->eta_mins,

                    'payment_amount' => $trip->payment_amount,

                    'delivery_name'  => $trip->delivery_name,
                    'delivery_phone' => $trip->delivery_phone,
                    'delivery_email' => $trip->delivery_email,

                    'package_description' => $trip->package_description,
                    'package_weight'      => $trip->package_weight,
                    'package_height'      => $trip->package_height,
                    'package_length'      => $trip->package_length,
                    'package_width'       => $trip->package_width,

                    'created_at' => optional($trip->created_at)->format('d M Y'),

                    'account' => $account ? [
                        'id'               => $account->id,
                        'opening_amount'   => $account->opening_amount,
                        'total_expense'    => $account->total_expense,
                        'remaining_amount' => $account->remaining_amount,
                        'status'           => $account->status,
                        'created_at'       => optional($account->created_at)->format('d M Y, h:i A'),

                        'transactions' => $account->transactions->map(function ($transaction) {
                            return [
                                'id'              => $transaction->id,
                                'type'            => $transaction->type,
                                'amount'          => $transaction->amount,
                                'expense_date'    => $transaction->expense_date
                                    ? \Carbon\Carbon::parse($transaction->expense_date)->format('d M Y')
                                    : optional($transaction->created_at)->format('d M Y'),

                                'title'           => $transaction->title,
                                'description'     => $transaction->description,

                                'source_type'     => $transaction->source_type,
                                'source_name'     => $transaction->source_name,
                                'source_id'       => $transaction->source_id,

                                'balance_before'  => $transaction->balance_before,
                                'balance_after'   => $transaction->balance_after,
                            ];
                        })->values(),
                    ] : null,
                ];
            })->values(),
        ]);
    }
}