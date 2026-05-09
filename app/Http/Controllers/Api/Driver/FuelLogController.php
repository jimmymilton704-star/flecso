<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\FuelLog;
use App\Models\Trip;
use App\Models\FuelAlert;
use Illuminate\Http\Request;

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

        // Create Fuel Log
        $fuelLog = FuelLog::create([
            'user_id' => $driver->admin_id ?? null,
            'trip_id' => $trip->id,
            'truck_id' => $trip->truck_id,
            'driver_id' => $driver->id,

            'fuel_liters' => $request->fuel_liters,
            'fuel_price_per_liter' => $request->fuel_price_per_liter,
            'total_cost' => $request->fuel_liters * $request->fuel_price_per_liter,

            'fuel_station' => $request->fuel_station,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'odometer_reading' => $request->odometer_reading,
            'notes' => $request->notes,
            'refuel_time' => $request->refuel_time,
        ]);

        // Update trip stats
        $this->updateTripFuelStats($trip);

        return response()->json([
            'status' => true,
            'message' => 'Fuel log created successfully',
            'data' => $fuelLog
        ]);
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

            FuelAlert::create([
                'trip_id' => $trip->id,
                'truck_id' => $trip->truck_id,
                'driver_id' => $trip->driver_id,
                'alert_type' => 'budget_exceeded',
                'message' => "Fuel budget exceeded! Budget: {$trip->fuel_budget}, Used: {$totalFuelCost}",
                'is_resolved' => false,
            ]);

            return; 
        }

        $distance = $trip->distance_km;
        $fuel = FuelLog::where('trip_id', $trip->id)->sum('fuel_liters');

        if ($distance <= 0 || $fuel <= 0) return;

        $kmPerLiter = $distance / $fuel;
        $costPerKm = $totalFuelCost / $distance;

        if ($kmPerLiter < 3) {
            FuelAlert::create([
                'trip_id' => $trip->id,
                'truck_id' => $trip->truck_id,
                'driver_id' => $trip->driver_id,
                'alert_type' => 'low_kmpl',
                'message' => "Low fuel efficiency: {$kmPerLiter} km/L",
                'is_resolved' => false,
            ]);
        } elseif ($costPerKm > 50) {
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
