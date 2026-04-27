<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminActivityTripsResource;
use App\Models\Container;
use App\Models\Trip;
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
                ->whereDate('date_time', now())
                ->where('status', 'active')
                ->distinct('driver_id')
                ->count('driver_id');

            /*
            |------------------------------------------------------------------
            | ACTIVE TRIPS
            |------------------------------------------------------------------
            */

            $activeTripsQuery = Trip::with([
                    'driver:id,name',
                    'truck',
                    'container'
                ])
                ->where('admin_id', $adminId)
                ->whereDate('date_time', now())
                ->where('status', 'active');

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
                    'driver:id,name',
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
}