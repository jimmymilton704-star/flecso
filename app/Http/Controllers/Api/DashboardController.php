<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminActivityTripsResource;
use App\Models\Container;
use App\Models\Trip;
use App\Models\Driver;
use App\Models\Truck;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {

            // Get logged-in admin automatically
            $adminId = auth()->id();

            /*
            |------------------------------------------------------------------
            | STATS SECTION
            |------------------------------------------------------------------
            */

            $activeTrucks = Truck::where('admin_id', $adminId)
                ->where('status', 'available')
                ->count();

            $activeContainers = Container::where('admin_id', $adminId)
                ->where('status', 'available')
                ->count();

            $driversOnDuty = Trip::where('admin_id', $adminId)
                ->whereDate('schedule_datetime', now())
                ->where('trip_status', 'active')
                ->distinct('driver_id')
                ->count('driver_id');

            /*
            |------------------------------------------------------------------
            | ACTIVE TRIPS
            |------------------------------------------------------------------
            */

            $activeTripsQuery = Trip::with([
                'driver:id,full_name',
                'truck',
                'container'
            ])
                ->where('admin_id', $adminId)
                ->whereDate('schedule_datetime', now())
                ->where('trip_status', 'active');

            $activeTrips = (clone $activeTripsQuery)
                ->latest()
                ->limit(5)
                ->get();

            $activeTripsCount = (clone $activeTripsQuery)->count();

            /*
            |------------------------------------------------------------------
            | ALL TRIPS
            |------------------------------------------------------------------
            */

            $allTrips = Trip::with([
                'driver:id,full_name',
                'truck',
                'container'
            ])
                ->where('admin_id', $adminId)
                ->latest()
                ->get();

            /*
            |------------------------------------------------------------------
            | RESPONSE
            |------------------------------------------------------------------
            */

            return response()->json([
                'status' => true,
                'message' => 'Dashboard fetched successfully',

                'data' => [
                    'stats' => [
                        'active_trucks'      => $activeTrucks,
                        'active_containers'  => $activeContainers,
                        'drivers_on_duty'    => $driversOnDuty,
                        'active_trips_count' => $activeTripsCount,
                    ],

                    'active_trips' => AdminActivityTripsResource::collection($activeTrips),

                    'all_trips' => AdminActivityTripsResource::collection($allTrips),
                ]
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => $ex->getMessage()
            ], 500);
        }
    }
    public function leaderboardApi(Request $request)
    {
        try {

            $adminId = auth()->id();

            $drivers = Driver::where('admin_id', $adminId)
                ->with(['trips', 'sosAlerts', 'fuelLogs'])
                ->get();

            // If no drivers found
            if ($drivers->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No data found',
                    'data' => []
                ], 404);
            }

            $leaderboard = $drivers->map(function ($driver) {

                /*
            |--------------------------------------------------------------
            | 1. ON-TIME DELIVERY SCORE (40%)
            |--------------------------------------------------------------
            */
                $totalTrips = $driver->trips->count();

                $onTimeTrips = $driver->trips
                    ->where('status', 'completed')
                    ->where('delivered_on_time', 1)
                    ->count();

                $onTimeScore = $totalTrips > 0
                    ? ($onTimeTrips / $totalTrips) * 100
                    : 0;

                /*
            |--------------------------------------------------------------
            | 2. INCIDENT SCORE (30%)
            |--------------------------------------------------------------
            */
                $incidentCount = $driver->sosAlerts->count();

                $incidentScore = max(0, 100 - ($incidentCount * 10));

                /*
            |--------------------------------------------------------------
            | 3. FUEL EFFICIENCY SCORE (30%)
            |--------------------------------------------------------------
            */
                $totalFuel = $driver->fuelLogs->sum('fuel_liters');

                $totalKm = $driver->trips->sum('distance_km');

                $kmPerLitre = $totalKm > 0
                    ? ($totalKm / max($totalFuel, 1))
                    : 0;

                $fuelScore = min(100, $kmPerLitre * 10);

                /*
            |--------------------------------------------------------------
            | FINAL SCORE
            |--------------------------------------------------------------
            */
                $finalScore =
                    ($onTimeScore * 0.4) +
                    ($incidentScore * 0.3) +
                    ($fuelScore * 0.3);

                return [
                    'driver_id'      => $driver->id,
                    'driver_name'    => $driver->full_name,
                    'on_time_score'  => round($onTimeScore, 2),
                    'incident_score' => round($incidentScore, 2),
                    'fuel_score'     => round($fuelScore, 2),
                    'final_score'    => round($finalScore, 2),
                ];
            });

            $leaderboard = $leaderboard
                ->sortByDesc('final_score')
                ->values();

            // If leaderboard empty
            if ($leaderboard->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No response data available',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Leaderboard fetched successfully',
                'data' => $leaderboard
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
