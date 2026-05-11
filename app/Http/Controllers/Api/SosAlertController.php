<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SosAlert;
use App\Models\FleetAlert;
use App\Models\FuelAlert;
use App\Models\FuelLog;
use App\Models\Truck;
use App\Models\TruckHealthLog;
use App\Models\TruckMaintenance;

use Illuminate\Http\Request;

class SosAlertController extends Controller
{
    /*
    |-----------------------------------------
    | ADMIN: GET ALL SOS ALERTS
    |-----------------------------------------
    */
    public function index(Request $request)
    {
        $adminId = auth()->id();
        $source = $request->alert_source;

        /*
    |--------------------------------------
    | SOS ALERTS
    |--------------------------------------
    */
        $sosAlerts = collect();

        if (!$source || $source === 'sos') {

            $sosQuery = SosAlert::with(['driver:id,full_name', 'trip.truck:id,truck_number'])
                ->where('admin_id', $adminId)
                ->latest();

            if ($request->filled('status')) {
                $sosQuery->where('status', $request->status);
            }

            $sosAlerts = $sosQuery->get()->map(function ($item) {
                $item->alert_source = 'sos';
                return $item;
            });
        }

        /*
    |--------------------------------------
    | FLEET ALERTS
    |--------------------------------------
    */
        $fleetAlerts = collect();

        if (!$source || $source === 'fleet') {

            $fleetQuery = FleetAlert::with(['truck:id,truck_number'])
                ->where('admin_id', $adminId)
                ->latest();

            if ($request->filled('status')) {
                $fleetQuery->where('status', $request->status);
            }

            $fleetAlerts = $fleetQuery->get()->map(function ($item) {
                $item->alert_source = 'fleet';
                $item->status = $item->is_read ? 'resolved' : 'pending';
                return $item;
            });
        }

        /*
    |--------------------------------------
    | FUEL ALERTS
    |--------------------------------------
    */
        $fuelAlerts = collect();

        if (!$source || $source === 'fuel') {

            $fuelQuery = FuelAlert::with(['driver:id,full_name', 'truck:id,truck_number'])
                ->whereHas('driver', function ($q) use ($adminId) {
                    $q->where('admin_id', $adminId);
                })
                ->latest();

            if ($request->filled('status')) {
                $fuelQuery->where('status', $request->status);
            }

            $fuelAlerts = $fuelQuery->get()->map(function ($item) {
                $item->alert_source = 'fuel';
                $item->status = $item->is_resolved ? 'resolved' : 'pending';
                return $item;
            });
        }

        /*
    |--------------------------------------
    | MERGE ALL
    |--------------------------------------
    */
        $merged = $sosAlerts
            ->concat($fleetAlerts)
            ->concat($fuelAlerts)
            ->sortByDesc('created_at')
            ->values();

        /*
    |--------------------------------------
    | STATS
    |--------------------------------------
    */
        $pendingCount = $merged->where('status', 'pending')->count();

        $pendingToday = $merged->where('status', 'pending')
            ->filter(fn($i) => $i->created_at >= now()->startOfDay())
            ->count();

        $resolvedToday = $merged->where('status', 'resolved')
            ->filter(fn($i) => $i->updated_at >= now()->startOfDay())
            ->count();

        /*
    |--------------------------------------
    | RESPONSE
    |--------------------------------------
    */
        return response()->json([
            'status' => true,
            'message' => 'Alerts fetched successfully',
            'data' => $merged,
            'stats' => [
                'pending_count' => $pendingCount,
                'pending_today' => $pendingToday,
                'resolved_today' => $resolvedToday,
            ]
        ]);
    }

    /*
    |-----------------------------------------
    | ADMIN: SINGLE SOS
    |-----------------------------------------
    */
    public function show($id)
    {
        $adminId = auth()->id();

        $alert = SosAlert::with(['driver', 'trip'])
            ->where('id', $id)
            ->where('admin_id', $adminId)
            ->first();

        if (!$alert) {
            return response()->json([
                'status' => false,
                'message' => 'SOS not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $alert
        ]);
    }


    public function fuelShowApi($id)
    {
        $adminId = auth()->id();

        $alert = FuelAlert::with(['driver', 'truck', 'trip'])
            ->where('id', $id)
            ->whereHas('driver', function ($q) use ($adminId) {
                $q->where('admin_id', $adminId);
            })
            ->first();

        if (!$alert) {
            return response()->json([
                'status' => false,
                'message' => 'Fuel Alert not found'
            ], 404);
        }

        $fuellog = FuelLog::where('truck_id', $alert->truck_id)
            ->latest()
            ->first();

        return response()->json([
            'status' => true,
            'data' => [
                'alert' => $alert,
                'fuel_log' => $fuellog,
            ]
        ]);
    }

    public function fleetShowApi($id)
    {
        $adminId = auth()->id();

        $alert = FleetAlert::with(['truck.driver'])
            ->where('id', $id)
            ->where('admin_id', $adminId)
            ->first();

        if (!$alert) {
            return response()->json([
                'status' => false,
                'message' => 'Fleet Alert not found'
            ], 404);
        }

        $truckId = $alert->truck_id;

        $truckHealth = TruckHealthLog::where('truck_id', $truckId)
            ->latest()
            ->first();

        $truckMaintenance = TruckMaintenance::where('truck_id', $truckId)
            ->latest()
            ->first();

        return response()->json([
            'status' => true,
            'data' => [
                'alert' => $alert,
                'truck_health' => $truckHealth,
                'truck_maintenance' => $truckMaintenance,
            ]
        ]);
    }
    /*
    |-----------------------------------------
    | ADMIN: RESOLVE SOS
    |-----------------------------------------
    */
    public function resolve(Request $request)
    {
        $adminId = auth()->id();

        $request->validate([
            'sos_id' => 'required|exists:sos_alerts,id',
        ]);

        $alert = SosAlert::where('id', $request->sos_id)
            ->where('admin_id', $adminId)
            ->first();

        if (!$alert) {
            return response()->json([
                'status' => false,
                'message' => 'SOS not found'
            ], 404);
        }

        $alert->update([
            'status' => 'resolved'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'SOS marked as resolved'
        ]);
    }
}
