<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Truck;
use App\Models\TruckHealthLog;
use App\Models\TruckMaintenance;
use App\Models\FleetAlert;
use Carbon\Carbon;

class MonitorFleetTrucks extends Command
{
    protected $signature = 'fleet:monitor-trucks';

    protected $description = 'Monitor truck maintenance and compliance status';

    public function handle()
    {
        $today = Carbon::today();

        $trucks = Truck::all();

        foreach ($trucks as $truck) {

            /*
            |--------------------------------------------------------------------------
            | HEALTH & DIAGNOSTICS
            |--------------------------------------------------------------------------
            */

            $latestHealth = TruckHealthLog::where('truck_id', $truck->id)
                ->latest('recorded_at')
                ->first();

            $maintenance = TruckMaintenance::where('truck_id', $truck->id)
                ->latest('id')
                ->first();

            if (
                $latestHealth &&
                $maintenance &&
                $maintenance->next_due_km
            ) {

                if ($latestHealth->current_km >= $maintenance->next_due_km) {
                    if (!$maintenance->scheduled_service_date) {

                        $maintenance->scheduled_date = now()->addDay()->toDateString();

                        $maintenance->status = 'scheduled';

                        $maintenance->save();
                    }


                    // Mark unavailable
                    $truck->status = 'Maintenance';
                    $truck->save();

                    // Prevent duplicate alerts
                    $exists = FleetAlert::where('truck_id', $truck->id)
                        ->where('type', 'maintenance_due')
                        ->whereDate('created_at', $today)
                        ->exists();

                    if (!$exists) {

                        FleetAlert::create([
                            'admin_id' => $truck->admin_id,
                            'truck_id' => $truck->id,
                            'type' => 'maintenance_due',
                            'priority' => 'high',
                            'message' => 'Maintenance Due: Truck #' . $truck->truck_number,
                            'is_read' => 0,
                        ]);
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | COMPLIANCE MONITOR
            |--------------------------------------------------------------------------
            */

            if ($truck->next_inspection_date) {

                $inspectionDate = Carbon::parse($truck->next_inspection_date);

                $daysLeft = $today->diffInDays($inspectionDate, false);

                /*
                |--------------------------------------------------------------------------
                | EXPIRING IN 30 DAYS
                |--------------------------------------------------------------------------
                */

                if ($daysLeft <= 30 && $daysLeft >= 0) {

                    $exists = FleetAlert::where('truck_id', $truck->id)
                        ->where('type', 'inspection_expiring')
                        ->whereDate('created_at', $today)
                        ->exists();

                    if (!$exists) {

                        FleetAlert::create([
                            'admin_id' => $truck->admin_id,
                            'truck_id' => $truck->id,
                            'type' => 'inspection_expiring',
                            'priority' => 'medium',
                            'message' => 'Truck #' . $truck->truck_number .
                                ' inspection expires in ' . $daysLeft . ' days.',
                            'is_read' => 0,
                        ]);
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | INSPECTION EXPIRED
                |--------------------------------------------------------------------------
                */

                if ($inspectionDate->isPast()) {

                    // Block truck
                    $truck->status = 'red';
                    $truck->save();

                    $exists = FleetAlert::where('truck_id', $truck->id)
                        ->where('type', 'inspection_expired')
                        ->whereDate('created_at', $today)
                        ->exists();

                    if (!$exists) {

                        FleetAlert::create([
                            'admin_id' => $truck->admin_id,
                            'truck_id' => $truck->id,
                            'type' => 'inspection_expired',
                            'priority' => 'high',
                            'message' => 'Truck #' . $truck->truck_number .
                                ' has an expired inspection.',
                            'is_read' => 0,
                        ]);
                    }
                }
            }
        }

        $this->info('Fleet monitoring completed successfully.');
    }
}
