<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\FuelLog;
use App\Models\Trip;
use App\Models\FuelAlert;
use App\Models\TripAccount;
use App\Models\TripAccountTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FuelLogController extends Controller
{
    /**
     * Store Fuel Log
     */
    public function store(Request $request)
    {
        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'fuel_liters' => 'required|numeric|min:0',
            'fuel_price_per_liter' => 'required|numeric|min:0',

            'fuel_station' => 'nullable|string',
            'location' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'odometer_reading' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'refuel_time' => 'nullable|date',
        ]);

        $driver = auth('driver')->user();

        $trip = Trip::where('id', $request->trip_id)
            ->where('driver_id', $driver->id)
            ->first();

        if (!$trip) {
            return response()->json([
                'status' => false,
                'message' => 'Trip not found or not assigned to you'
            ], 404);
        }

        try {
            $fuelLog = DB::transaction(function () use ($request, $driver, $trip) {

                $totalCost = $request->fuel_liters * $request->fuel_price_per_liter;

                /*
                |--------------------------------------------------------------------------
                | Create Fuel Log
                |--------------------------------------------------------------------------
                */
                $fuelLog = FuelLog::create([
                    'user_id' => $driver->admin_id ?? null,
                    'trip_id' => $trip->id,
                    'truck_id' => $trip->truck_id,
                    'driver_id' => $driver->id,

                    'fuel_liters' => $request->fuel_liters,
                    'fuel_price_per_liter' => $request->fuel_price_per_liter,
                    'total_cost' => $totalCost,

                    'fuel_station' => $request->fuel_station,
                    'location' => $request->location,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'odometer_reading' => $request->odometer_reading,
                    'notes' => $request->notes,
                    'refuel_time' => now()->format('Y-m-d H:i:s'),
                ]);

                /*
                |--------------------------------------------------------------------------
                | Create Trip Account Transaction + Deduct Balance
                |--------------------------------------------------------------------------
                */
                $this->createFuelTripTransaction($trip, $driver, $fuelLog, $totalCost);

                /*
                |--------------------------------------------------------------------------
                | Update Trip Fuel Stats
                |--------------------------------------------------------------------------
                */
                $this->updateTripFuelStats($trip);

                return $fuelLog;
            });

            return response()->json([
                'status' => true,
                'message' => 'Fuel log created successfully and amount deducted from trip account.',
                'data' => $fuelLog
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Create Fuel Transaction and Deduct Amount
     */
    private function createFuelTripTransaction(Trip $trip, $driver, FuelLog $fuelLog, $totalCost)
    {
        $account = TripAccount::where('trip_id', $trip->id)
            ->where('admin_id', $driver->admin_id)
            ->lockForUpdate()
            ->first();

        if (!$account) {
            throw new \Exception('Trip account not found for this trip.');
        }

        if ($totalCost <= 0) {
            throw new \Exception('Fuel cost must be greater than zero.');
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Transaction For Same Fuel Log
        |--------------------------------------------------------------------------
        */
        $alreadyExists = TripAccountTransaction::where('trip_account_id', $account->id)
            ->where('source_type', 'fuel_log')
            ->where('source_id', $fuelLog->id)
            ->exists();

        if ($alreadyExists) {
            return null;
        }

        $balanceBefore = $account->remaining_amount;
        $deductAmount = (float) $totalCost;
        $balanceAfter = $balanceBefore - $totalCost;

        if ($balanceAfter < 0) {
            throw new \Exception(
                'Insufficient trip account balance. ' .
                    'Balance Before: ' . $balanceBefore . ', ' .
                    'Deduct Amount: ' . $deductAmount . ', ' .
                    'Balance After: ' . $balanceAfter . ', ' .
                    'Short Amount: ' . abs($balanceAfter)
            );
        }

        $transaction = TripAccountTransaction::create([
            'trip_account_id' => $account->id,
            'trip_id' => $trip->id,
            'driver_id' => $driver->id,

            'type' => 'fuel',
            'amount' => $totalCost,
            'expense_date' => $fuelLog->refuel_time
                ? date('Y-m-d', strtotime($fuelLog->refuel_time))
                : now()->toDateString(),

            'title' => 'Fuel Expense',
            'description' => 'Fuel added by driver. Liters: ' . $fuelLog->fuel_liters . ', Price/Liter: ' . $fuelLog->fuel_price_per_liter,

            'source_type' => 'fuel_log',
            'source_name' => 'Fuel Log',
            'source_id' => $fuelLog->id,

            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
        ]);

        $account->update([
            'total_expense' => $account->total_expense + $totalCost,
            'remaining_amount' => $balanceAfter,
        ]);

        return $transaction;
    }

    /**
     * Recalculate trip fuel stats
     */
    public function tripFuelLogs(Request $request)
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

        $this->updateTripFuelStats($trip);

        return response()->json([
            'status' => true,
            'message' => 'Trip fuel stats updated',
            'data' => $trip->fresh()
        ]);
    }

    /**
     * Shared calculation logic
     */
    private function updateTripFuelStats(Trip $trip)
    {
        $totalFuelCost = FuelLog::where('trip_id', $trip->id)
            ->sum('total_cost');

        $totalFuelLiters = FuelLog::where('trip_id', $trip->id)
            ->sum('fuel_liters');

        $fuelCostPerKm = 0;
        $avgKmpl = 0;

        if ($trip->distance_km > 0) {
            $fuelCostPerKm = $totalFuelCost / $trip->distance_km;
            $avgKmpl = $totalFuelLiters > 0
                ? $trip->distance_km / $totalFuelLiters
                : 0;
        }

        $trip->update([
            'total_fuel_cost' => $totalFuelCost,
            'total_fuel_liters' => $totalFuelLiters,
            'fuel_cost_per_km' => round($fuelCostPerKm, 2),
            'avg_kmpl' => round($avgKmpl, 2),
        ]);

        $this->generateFuelAlerts($trip);

        return $trip;
    }

    private function generateFuelAlerts(Trip $trip)
    {
        $totalFuelCost = FuelLog::where('trip_id', $trip->id)->sum('total_cost');

        if ($trip->payment_amount > 0 && $totalFuelCost > $trip->payment_amount) {

            /*
            |--------------------------------------------------------------------------
            | Prevent Same Budget Alert Again and Again
            |--------------------------------------------------------------------------
            */
            $exists = FuelAlert::where('trip_id', $trip->id)
                ->where('alert_type', 'budget_exceeded')
                ->where('is_resolved', false)
                ->exists();

            if (!$exists) {
                FuelAlert::create([
                    'trip_id' => $trip->id,
                    'truck_id' => $trip->truck_id,
                    'driver_id' => $trip->driver_id,
                    'alert_type' => 'budget_exceeded',
                    'message' => "Fuel budget exceeded! Budget: {$trip->payment_amount}, Used: {$totalFuelCost}",
                    'is_resolved' => false,
                ]);
            }

            return;
        }

        $distance = $trip->distance_km;
        $fuel = FuelLog::where('trip_id', $trip->id)->sum('fuel_liters');

        if ($distance <= 0 || $fuel <= 0) {
            return;
        }

        $kmPerLiter = $distance / $fuel;
        $costPerKm = $totalFuelCost / $distance;

        if ($kmPerLiter < 3) {
            $exists = FuelAlert::where('trip_id', $trip->id)
                ->where('alert_type', 'low_kmpl')
                ->where('is_resolved', false)
                ->exists();

            if (!$exists) {
                FuelAlert::create([
                    'trip_id' => $trip->id,
                    'truck_id' => $trip->truck_id,
                    'driver_id' => $trip->driver_id,
                    'alert_type' => 'low_kmpl',
                    'message' => "Low fuel efficiency: {$kmPerLiter} km/L",
                    'is_resolved' => false,
                ]);
            }
        } elseif ($costPerKm > 50) {
            $exists = FuelAlert::where('trip_id', $trip->id)
                ->where('alert_type', 'high_cost')
                ->where('is_resolved', false)
                ->exists();

            if (!$exists) {
                FuelAlert::create([
                    'trip_id' => $trip->id,
                    'truck_id' => $trip->truck_id,
                    'driver_id' => $trip->driver_id,
                    'alert_type' => 'high_cost',
                    'message' => "High cost per KM: {$costPerKm}",
                    'is_resolved' => false,
                ]);
            }
        }
    }
}
