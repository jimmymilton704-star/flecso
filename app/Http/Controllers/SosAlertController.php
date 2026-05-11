<?php

namespace App\Http\Controllers;

use App\Models\SosAlert;
use App\Models\FleetAlert;
use App\Models\Driver;
use App\Models\FuelAlert;
use App\Models\FuelLog;
use App\Models\Truck;
use App\Models\TruckHealthLog;
use App\Models\TruckMaintenance;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SosAlertController extends Controller
{
    /*
    |-----------------------------------------
    | LIST ALL SOS ALERTS (WEB)
    |-----------------------------------------
    */
    public function index(Request $request)
    {
        $adminId = auth()->id();
        $source = $request->alert_source; // Get the filter value

        /*
    |--------------------------------------------------------------------------
    | 1. SOS ALERTS
    |--------------------------------------------------------------------------
    */
        $sosAlerts = collect();
        // Only query if no source is selected (All) OR source is 'sos'
        if (!$source || $source === 'sos') {
            $sosQuery = SosAlert::with(['driver:id,full_name', 'trip.truck:id,truck_number'])
                ->where('admin_id', $adminId);

            if ($request->filled('status')) {
                $sosQuery->where('status', $request->status);
            }

            $sosAlerts = $sosQuery->get()->map(function ($item) {
                $item->alert_source = 'sos';
                return $item;
            });
        }

        /*
    |--------------------------------------------------------------------------
    | 2. FLEET ALERTS
    |--------------------------------------------------------------------------
    */
        $fleetAlerts = collect();
        // Only query if no source is selected (All) OR source is 'fleet'
        if (!$source || $source === 'fleet') {
            $fleetQuery = FleetAlert::with(['truck:id,truck_number'])
                ->where('admin_id', $adminId);

            if ($request->filled('status')) {
                $fleetQuery->where('status', $request->status);
            }

            $fleetAlerts = $fleetQuery->get()->map(function ($item) {
                $item->alert_source = 'fleet';
                $item->status = ($item->is_read == 1) ? 'resolved' : 'pending';
                return $item;
            });
        }

        /*
    |--------------------------------------------------------------------------
    | 3. FUEL ALERTS
    |--------------------------------------------------------------------------
    */
        $fuelAlerts = collect();
        // Only query if no source is selected (All) OR source is 'fuel'
        if (!$source || $source === 'fuel') {
            $fuelQuery = FuelAlert::with(['driver:id,full_name', 'truck:id,truck_number'])
                ->whereHas('driver', function ($q) use ($adminId) {
                    $q->where('admin_id', $adminId);
                });

            if ($request->filled('status')) {
                $fuelQuery->where('status', $request->status);
            }

            $fuelAlerts = $fuelQuery->get()->map(function ($item) {
                $item->alert_source = 'fuel';
                $item->status = ($item->is_resolved == 1) ? 'resolved' : 'pending';
                return $item;
            });
        }

        /*
    |--------------------------------------------------------------------------
    | MERGE & PAGINATE
    |--------------------------------------------------------------------------
    */
        $merged = $sosAlerts
            ->concat($fleetAlerts)
            ->concat($fuelAlerts)
            ->sortByDesc('created_at')
            ->values();

        $perPage = 10;
        $page = request()->get('page', 1);

        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $merged->forPage($page, $perPage),
            $merged->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        /*
    |--------------------------------------------------------------------------
    | STATS (Calculated from the filtered set or global - usually global is better)
    |--------------------------------------------------------------------------
    */

        $pendingCount = $merged->where('status', 'pending')->count();
        $pendingToday = $merged->where('status', 'pending')
            ->filter(fn($item) => $item->created_at >= now()->startOfDay())->count();
        $resolvedToday = $merged->where('status', 'resolved')
            ->filter(fn($item) => $item->updated_at >= now()->startOfDay())->count();

        return view('alerts.index', [
            'alerts' => $paginated,
            'status' => $request->status,
            'alert_source' => $source,
            'pendingCount' => $pendingCount,
            'pendingToday' => $pendingToday,
            'resolvedToday' => $resolvedToday,
        ]);
    }

    /*
    |-----------------------------------------
    | SINGLE SOS VIEW
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
            return redirect()->route('alerts.index')
                ->with('error', 'SOS Alert not found');
        }

        return view('alerts.show', [
            'alert' => $alert
        ]);
    }
    public function Fuelshow($id)
    {
        $adminId = auth()->id();

        $alert = FuelAlert::with(['driver', 'truck', 'trip'])
            ->where('id', $id)
            ->whereHas('driver', function ($q) use ($adminId) {
                $q->where('admin_id', $adminId);
            })
            ->first();

        if (!$alert) {
            return redirect()->route('alerts.index')
                ->with('error', 'Fuel Alert not found');
        }

        $truckid = $alert->truck_id;

        $fuellog = FuelLog::where('truck_id', $truckid)
            ->latest()
            ->first();

        return view('alerts.fuelshow', [
            'alert' => $alert,
            'fuellog' => $fuellog,
        ]);
    }
    public function Fleetshow($id)
    {
        $adminId = auth()->id();

        $alert = FleetAlert::with(['truck'])
            ->where('id', $id)
            ->where('admin_id', $adminId)
            ->first();
        $truckid = $alert->truck_id;
        $truckhealth = TruckHealthLog::where('truck_id', $truckid)->latest()->first();
        $truckMaintainance = TruckMaintenance::where('truck_id', $truckid)->latest()->first();

        if (!$alert) {
            return redirect()->route('alerts.index')
                ->with('error', 'Fleet Alert not found');
        }

        return view('alerts.fleetshow', [
            'alert' => $alert,
            'truckhealth' => $truckhealth,
            'truckMaintainance' => $truckMaintainance,
        ]);
    }

    /*
    |-----------------------------------------
    | RESOLVE SOS (WEB)
    |-----------------------------------------
    */
    public function resolve(Request $request)
    {
        $adminId = auth()->id();

        $request->validate([
            'alert_id' => 'required|integer',
            'source'   => 'required|in:sos,fleet,fuel',
        ]);

        $id = $request->alert_id;
        $source = $request->source;

        try {
            if ($source === 'sos') {
                $alert = SosAlert::where('id', $id)->where('admin_id', $adminId)->first();
                if ($alert) {
                    $alert->update(['status' => 'resolved']);
                }
            } elseif ($source === 'fleet') {
                $alert = FleetAlert::where('id', $id)->where('admin_id', $adminId)->first();
                if ($alert) {
                    $alert->update(['is_read' => 1]);
                }
            } elseif ($source === 'fuel') {
                $alert = FuelAlert::where('id', $id)->first();
                if ($alert) {
                    $alert->update(['is_resolved' => 1]);
                }
            }

            if (!$alert) {
                return back()->with('error', 'Alert not found or unauthorized.');
            }

            return back()->with('success', ucfirst($source) . ' alert marked as resolved.');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
