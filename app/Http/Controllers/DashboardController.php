<?php

namespace App\Http\Controllers;

use App\Models\Container;
use App\Models\Trip;
use App\Models\Truck;
use App\Models\Driver;
use App\Models\SosAlert;
use App\Models\DriverLocation;
use Illuminate\Http\Request;
use App\Http\Resources\AdminActivityTripsResource;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {

            $adminId = auth()->id();

            /*
            |------------------------------------------------------------------
            | BASIC TOTALS
            |------------------------------------------------------------------
            */

            $totalTrucks = Truck::where('admin_id', $adminId)->count();

            $totalContainers = Container::where('admin_id', $adminId)->count();

            $totalDrivers = Driver::where('admin_id', $adminId)->count();


            /*
            |------------------------------------------------------------------
            | TRIP COUNTS
            |------------------------------------------------------------------
            */

            $activeTrips = Trip::where('admin_id', $adminId)
                ->where('trip_status', 'active')
                ->count();

            $completedTrips = Trip::where('admin_id', $adminId)
                ->where('trip_status', 'completed')
                ->count();

            $ongoingTrips = Trip::where('admin_id', $adminId)
                ->where('trip_status', 'in_transit')
                ->count();

            $cancelledTrips = Trip::where('admin_id', $adminId)
                ->where('trip_status', 'cancelled')
                ->count();

            /*
            |------------------------------------------------------------------
            | SOS ALERTS
            |------------------------------------------------------------------
            */

            $sosAlerts = SosAlert::with(['driver:id,full_name', 'trip'])
                ->where('admin_id', $adminId)
                ->latest()
                ->limit(10) // limit for dashboard
                ->get();

            $sosAlertsCount = SosAlert::where('admin_id', $adminId)->count();


            /*
            |------------------------------------------------------------------
            | RECENT TRIPS
            |------------------------------------------------------------------
            */

            $recentTrips = Trip::with(['driver:id,full_name', 'truck', 'container'])
                ->where('admin_id', $adminId)
                ->latest()
                ->limit(5)
                ->get();


            /*
            |------------------------------------------------------------------
            | ALL TRIPS (FOR FILTERING IN UI)
            |------------------------------------------------------------------
            */

            $allTrips = Trip::with(['driver:id,full_name', 'truck', 'container'])
                ->where('admin_id', $adminId)
                ->latest()
                ->limit(50) // prevent heavy load
                ->get();



            /*
                |------------------------------------------------------------------
                | LIVE DRIVER LOCATIONS
                |------------------------------------------------------------------
                */

            $driverLocations = \App\Models\DriverLocation::with([
                'driver:id,full_name,admin_id'
            ])
                ->whereHas('driver', function ($q) use ($adminId) {
                    $q->where('admin_id', $adminId);
                })
                ->latest()
                ->get()
                ->unique('driver_id')
                ->values();



            /*
            |------------------------------------------------------------------
            | RETURN VIEW
            |------------------------------------------------------------------
            */

            return view('dashboard.index', [

                // KPI
                'total_trucks'       => $totalTrucks,
                'total_containers'   => $totalContainers,
                'total_drivers'      => $totalDrivers,

                'active_trips'       => $activeTrips,
                'completed_trips'    => $completedTrips,
                'ongoing_trips'      => $ongoingTrips,
                'cancelled_trips'    => $cancelledTrips,

                // SOS
                'sos_alerts_count'   => $sosAlertsCount,
                'sos_alerts'         => $sosAlerts,

                // Trips
                'recent_trips'       => AdminActivityTripsResource::collection($recentTrips),
                'all_trips'          => $allTrips,
                'driver_locations' => $driverLocations,
            ]);
        } catch (\Exception $ex) {

            return back()->with('error', $ex->getMessage());
        }
    }

    public function leaderboard(Request $request)
    {
        $adminId = auth()->id();

        $drivers = Driver::where('admin_id', $adminId)
            ->with(['trips', 'sosAlerts', 'fuelLogs'])
            ->get();

        $leaderboard = $drivers->map(function ($driver) {

            /*
        |------------------------------------------------------------------
        | 1. ON-TIME DELIVERY SCORE  (weight: 40 %)
        |    % of completed trips marked as on-time
        |------------------------------------------------------------------
        */
            $totalTrips  = $driver->trips->count();
            $onTimeTrips = $driver->trips
                ->where('status', 'completed')
                ->where('delivered_on_time', 1)
                ->count();

            $onTimeScore = $totalTrips > 0
                ? ($onTimeTrips / $totalTrips) * 100
                : 0;

            /*
        |------------------------------------------------------------------
        | 2. INCIDENT / SAFETY SCORE  (weight: 30 %)
        |    Starts at 100, deduct 10 per SOS alert, floor at 0
        |------------------------------------------------------------------
        */
            $incidentCount = $driver->sosAlerts->count();
            $incidentScore = max(0, 100 - ($incidentCount * 10));

            /*
        |------------------------------------------------------------------
        | 3. FUEL EFFICIENCY SCORE  (weight: 30 %)
        |    km-per-litre → normalise to 0-100 (cap at 10 km/L = 100 pts)
        |------------------------------------------------------------------
        */
            $totalFuel = $driver->fuelLogs->sum('fuel_liters');
            $totalKm   = $driver->trips->sum('distance_km');

            $kmPerLitre = $totalKm > 0
                ? ($totalKm / max($totalFuel, 1))
                : 0;

            $fuelScore = min(100, $kmPerLitre * 10);   // 10 km/L → 100 pts

            /*
        |------------------------------------------------------------------
        | FINAL WEIGHTED SCORE
        |------------------------------------------------------------------
        */
            $finalScore =
                ($onTimeScore  * 0.4) +
                ($incidentScore * 0.3) +
                ($fuelScore    * 0.3);

            return [
                'driver'         => $driver,
                'on_time_score'  => round($onTimeScore,  2),
                'incident_score' => round($incidentScore, 2),
                'fuel_score'     => round($fuelScore,     2),
                'final_score'    => round($finalScore,    2),
            ];
        });

        // Sort descending and re-index so $index in Blade = 0-based rank
        $leaderboard = $leaderboard->sortByDesc('final_score')->values();

        return view('dashboard.leaderboard', compact('leaderboard'));
    }
}
