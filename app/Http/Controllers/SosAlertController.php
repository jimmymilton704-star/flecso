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
use App\Models\TripAccount;
use App\Models\TripAccountTransaction;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
    private function createTripTransaction($data)
    {
        $account = TripAccount::where('trip_id', $data['trip_id'])
            ->where('admin_id', $data['admin_id'])
            ->lockForUpdate()
            ->first();

        if (!$account) {
            throw new \Exception('Trip account not found.');
        }

        $amount = (float) $data['amount'];

        if ($amount <= 0) {
            throw new \Exception('Transaction amount must be greater than zero.');
        }

        $alreadyExists = TripAccountTransaction::where('trip_account_id', $account->id)
            ->where('source_type', $data['source_type'])
            ->where('source_id', $data['source_id'])
            ->exists();

        if ($alreadyExists) {
            return null;
        }

        $balanceBefore = $account->remaining_amount;
        $balanceAfter = $balanceBefore - $amount;

        if ($balanceAfter < 0) {
            throw new \Exception('Insufficient trip account balance.');
        }

        $transaction = TripAccountTransaction::create([
            'trip_account_id' => $account->id,
            'trip_id' => $data['trip_id'],
            'driver_id' => $data['driver_id'],
            'type' => $data['type'],
            'amount' => $amount,
            'expense_date' => $data['expense_date'] ?? now()->toDateString(),
            'title' => $data['title'] ?? ucfirst(str_replace('_', ' ', $data['type'])),
            'description' => $data['description'] ?? null,
            'source_type' => $data['source_type'],
            'source_name' => $data['source_name'],
            'source_id' => $data['source_id'],
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
        ]);

        $account->update([
            'total_expense' => $account->total_expense + $amount,
            'remaining_amount' => $balanceAfter,
        ]);

        return $transaction;
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

            // alert source
            'source' => 'required|string|in:sos,fleet,fuel,toll_tax,maintenance,food,advance,other',

            // transaction data
            'trip_id' => 'required|integer|exists:trips,id',
            'driver_id' => 'required|integer|exists:drivers,id',
            'amount' => 'required|numeric|min:1',
            'transaction_type' => 'required|string|in:fuel,toll_tax,maintenance,food,advance,other,sos,fleet',
            'source_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'expense_date' => 'nullable|date',
        ]);

        try {
            DB::transaction(function () use ($request, $adminId) {

                $source = $request->source;
                $alertId = $request->alert_id;

                /*
            |--------------------------------------------------------------------------
            | Mark Alert Resolved According To Source
            |--------------------------------------------------------------------------
            */

                if ($source === 'sos') {
                    $alert = SosAlert::where('id', $alertId)
                        ->where('admin_id', $adminId)
                        ->first();

                    if (!$alert) {
                        throw new \Exception('SOS alert not found or unauthorized.');
                    }

                    $alert->update([
                        'status' => 'resolved',
                    ]);
                } elseif ($source === 'fleet') {
                    $alert = FleetAlert::where('id', $alertId)
                        ->where('admin_id', $adminId)
                        ->first();

                    if (!$alert) {
                        throw new \Exception('Fleet alert not found or unauthorized.');
                    }

                    $alert->update([
                        'is_read' => 1,
                    ]);
                } elseif ($source === 'fuel') {
                    $alert = FuelAlert::where('id', $alertId)
                        ->whereHas('driver', function ($q) use ($adminId) {
                            $q->where('admin_id', $adminId);
                        })
                        ->first();

                    if (!$alert) {
                        throw new \Exception('Fuel alert not found or unauthorized.');
                    }

                    $alert->update([
                        'is_resolved' => 1,
                    ]);
                }

                /*
            |--------------------------------------------------------------------------
            | Create Trip Account Transaction For Any Source
            |--------------------------------------------------------------------------
            */

                $this->createTripTransaction([
                    'admin_id' => $adminId,
                    'trip_id' => $request->trip_id,
                    'driver_id' => $request->driver_id,

                    'type' => $request->transaction_type,
                    'amount' => $request->amount,
                    'expense_date' => $request->expense_date ?? now()->toDateString(),

                    'title' => ucfirst(str_replace('_', ' ', $request->transaction_type)),
                    'description' => $request->description,

                    'source_type' => $source,
                    'source_name' => $request->source_name ?? ucfirst(str_replace('_', ' ', $source)),
                    'source_id' => $alertId,
                ]);
            });

            return back()->with('success', 'Alert resolved and transaction created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
