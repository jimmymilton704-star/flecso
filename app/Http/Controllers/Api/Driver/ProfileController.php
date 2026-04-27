<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Trip;
use App\Models\SosAlert;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /*
    |-----------------------------------------
    | GET DRIVER PROFILE
    |-----------------------------------------
    */
    public function show(Request $request)
    {
        $driver = auth('driver')->user();

        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized driver'
            ], 401);
        }

        return response()->json([
            'status' => true,
            'data' => $driver
        ]);
    }

    /*
    |-----------------------------------------
    | UPDATE DRIVER PROFILE
    |-----------------------------------------
    */
    public function update(Request $request)
    {
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
        | PHOTO UPDATE
        |-----------------------------------------
        */
        if ($request->hasFile('driver_photo')) {

            if ($driver->driver_photo && file_exists(public_path($driver->driver_photo))) {
                unlink(public_path($driver->driver_photo));
            }

            $file = $request->file('driver_photo');
            $name = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/drivers'), $name);

            $data['driver_photo'] = 'uploads/drivers/'.$name;
        }

        $driver->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'data' => $driver
        ]);
    }

    /*
    |-----------------------------------------
    | DELETE DRIVER (FULL CLEANUP)
    |-----------------------------------------
    */
    public function delete(Request $request)
    {
        $driver = auth('driver')->user();

        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized driver'
            ], 401);
        }

        /*
        |-----------------------------------------
        | DELETE RELATED DATA
        |-----------------------------------------
        */
        Trip::where('driver_id', $driver->id)->delete();

        SosAlert::where('driver_id', $driver->id)->delete();

        if ($driver->driver_photo && file_exists(public_path($driver->driver_photo))) {
            unlink(public_path($driver->driver_photo));
        }

        $driver->delete();

        return response()->json([
            'status' => true,
            'message' => 'Driver and all related data deleted successfully'
        ]);
    }
}