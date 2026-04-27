<?php

namespace App\Http\Controllers;

use App\Models\Container;
use App\Models\Trip;
use App\Models\Truck;
use App\Models\Driver;
use App\Models\SosAlert;
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
                ->where('trip_status', 'ongoing')
                ->count();

            $cancelledTrips = Trip::where('admin_id', $adminId)
                ->where('trip_status', 'cancelled')
                ->count();

            /*
            |------------------------------------------------------------------
            | SOS ALERTS
            |------------------------------------------------------------------
            */

            $sosAlerts = SosAlert::with(['driver:id,name', 'trip'])
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

            $recentTrips = Trip::with(['driver:id,name', 'truck', 'container'])
                ->where('admin_id', $adminId)
                ->latest()
                ->limit(5)
                ->get();


            /*
            |------------------------------------------------------------------
            | ALL TRIPS (FOR FILTERING IN UI)
            |------------------------------------------------------------------
            */

            $allTrips = Trip::with(['driver:id,name', 'truck', 'container'])
                ->where('admin_id', $adminId)
                ->latest()
                ->limit(50) // prevent heavy load
                ->get();


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
            ]);

        } catch (\Exception $ex) {

            return back()->with('error', $ex->getMessage());
        }
    }
}