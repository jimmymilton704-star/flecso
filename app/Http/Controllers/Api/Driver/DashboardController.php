<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {

            /*
            |-----------------------------------------
            | GET AUTHENTICATED DRIVER (SAFE)
            |-----------------------------------------
            */
            $driver = auth('driver')->user();

            if (!$driver) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized driver'
                ], 401);
            }

            $driverId = $driver->id;

            $fromDate = Carbon::now()->subDays(30);
            $toDate   = Carbon::now();

            /*
            |-----------------------------------------
            | STATS
            |-----------------------------------------
            */
            $totalTrips = Trip::where('driver_id', $driverId)
                ->whereBetween('schedule_datetime', [$fromDate, $toDate])
                ->count();

            $completedTrips = Trip::where('driver_id', $driverId)
                ->where('trip_status', 'completed')
                ->count();

            $pendingTrips = Trip::where('driver_id', $driverId)
                ->where('trip_status', 'pending')
                ->count();

            $cancelledTrips = Trip::where('driver_id', $driverId)
                ->where('trip_status', 'cancelled')
                ->count();

            /*
            |-----------------------------------------
            | ACTIVE TRIP
            |-----------------------------------------
            */
            $activeTrip = Trip::where('driver_id', $driverId)
                ->where('trip_status', 'in_progress')
                ->latest()
                ->first();

            /*
            |-----------------------------------------
            | UPCOMING TRIPS
            |-----------------------------------------
            */
            $upcomingTrips = Trip::where('driver_id', $driverId)
                ->where('trip_status', 'pending')
                ->where('schedule_datetime', '>=', Carbon::now())
                ->orderBy('schedule_datetime', 'asc')
                ->get();

            /*
            |-----------------------------------------
            | RESPONSE
            |-----------------------------------------
            */
            return response()->json([
                'status' => true,
                'data' => [
                    'last_30_days_trips' => $totalTrips,
                    'completed_trips'    => $completedTrips,
                    'pending_trips'      => $pendingTrips,
                    'cancelled_trips'    => $cancelledTrips,
                    'active_trip'        => $activeTrip,
                    'upcoming_trips'     => $upcomingTrips,
                ]
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}