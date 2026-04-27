<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DriverLocation;
use Illuminate\Http\Request;

class LiveTrackingController extends Controller
{
    /*
    |-----------------------------------------
    | GET ALL LIVE DRIVER LOCATIONS
    |-----------------------------------------
    */
    public function liveDrivers()
    {
        $adminId = auth()->id();

        $locations = DriverLocation::with('driver')
            ->whereHas('driver', function ($q) use ($adminId) {
                $q->where('admin_id', $adminId);
            })
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'data' => $locations
        ]);
    }
}