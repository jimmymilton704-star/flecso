<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\SosAlert;
use Illuminate\Http\Request;

class SosAlertController extends Controller
{
    /*
    |-----------------------------------------
    | DRIVER: CREATE SOS ALERT
    |-----------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'trip_id'         => 'required|exists:trips,id',
            'emergency_type'  => 'required|string',
            'description'     => 'nullable|string',
            'location'        => 'nullable|string',
            'photo'           => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        /*
        |-----------------------------------------
        | AUTH DRIVER
        |-----------------------------------------
        */
        $driver = auth('driver')->user();

        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized driver'
            ], 401);
        }

        $data = $request->all();

        /*
        |-----------------------------------------
        | AUTO ASSIGN IDS (NO SPOOFING)
        |-----------------------------------------
        */
        $data['driver_id'] = $driver->id;
        $data['admin_id']  = $driver->admin_id;

        /*
        |-----------------------------------------
        | PHOTO UPLOAD
        |-----------------------------------------
        */
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $name = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/sos'), $name);
            $data['photo'] = 'uploads/sos/'.$name;
        }

        $data['status'] = 'pending';
        

        $sos = SosAlert::create($data);

        return response()->json([
            'status' => true,
            'message' => 'SOS alert created successfully',
            'data' => $sos
        ]);
    }

    /*
    |-----------------------------------------
    | DRIVER: MY SOS HISTORY
    |-----------------------------------------
    */
    public function driverHistory(Request $request)
    {
        $driver = auth('driver')->user();

        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized driver'
            ], 401);
        }

        $alerts = SosAlert::where('driver_id', $driver->id)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'data' => $alerts
        ]);
    }
}