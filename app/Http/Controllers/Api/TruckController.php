<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Truck;
use Illuminate\Http\Request;

class TruckController extends Controller
{
    /*
    |-----------------------------------------
    | GET ALL TRUCKS
    |-----------------------------------------
    */
    public function index(Request $request)
    {
        $adminId = auth()->id();

        $trucks = Truck::where('admin_id', $adminId)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Trucks fetched successfully',
            'data' => $trucks
        ]);
    }

    /*
    |-----------------------------------------
    | STORE TRUCK
    |-----------------------------------------
    */
    public function store(Request $request)
    {
        $adminId = auth()->id();

        $request->validate([
            'truck_number'         => 'required|string',
            'truck_license_number' => 'required|string',
            'capacity_tons'        => 'required|numeric',
            'truck_type_category'  => 'required|string',
            'type'                 => 'required|string',
            'status'              => 'required|string',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'license_plate_number'   => 'required|string',
            'vin_number'             => 'nullable|string',
            'first_registration_date'=> 'nullable|date',
            'usage_type'             => 'nullable|string',
            'documento_unico'        => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',

            'vehicle_category'    => 'nullable|string',
            'gvw_kg'              => 'nullable|numeric',
            'payload_capacity_kg' => 'nullable|numeric',
            'number_of_axles'     => 'nullable|integer',
            'engine_class'        => 'nullable|string',
            'fuel_type'           => 'nullable|string',

            'next_inspection_date'   => 'nullable|date',
            'insurance_policy_number'=> 'nullable|string',
            'insurance_expiry_date'  => 'nullable|date',
            'tachograph_calibration_expiry' => 'nullable|date',
            'bollo_expiry_date'      => 'nullable|date',
        ]);

        $data = $request->all();
        $data['admin_id'] = $adminId;

        /*
        | IMAGE UPLOAD
        */
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

            $path = public_path('uploads/trucks');
            if (!file_exists($path)) mkdir($path, 0755, true);

            $file->move($path, $fileName);
            $data['image'] = 'uploads/trucks/'.$fileName;
        }

        /*
        | DOCUMENT UPLOAD
        */
        if ($request->hasFile('documento_unico')) {
            $file = $request->file('documento_unico');
            $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

            $path = public_path('uploads/trucks/docs');
            if (!file_exists($path)) mkdir($path, 0755, true);

            $file->move($path, $fileName);
            $data['documento_unico'] = 'uploads/trucks/docs/'.$fileName;
        }

        $truck = Truck::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Truck created successfully',
            'data' => $truck
        ]);
    }

    /*
    |-----------------------------------------
    | UPDATE TRUCK
    |-----------------------------------------
    */
    public function update(Request $request, $id)
    {
        $adminId = auth()->id();

        $truck = Truck::where('id', $id)
            ->where('admin_id', $adminId)
            ->firstOrFail();

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($truck->image && file_exists(public_path($truck->image))) {
                unlink(public_path($truck->image));
            }

            $file = $request->file('image');
            $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

            $path = public_path('uploads/trucks');
            if (!file_exists($path)) mkdir($path, 0755, true);

            $file->move($path, $fileName);
            $data['image'] = 'uploads/trucks/'.$fileName;
        }

        if ($request->hasFile('documento_unico')) {
            if ($truck->documento_unico && file_exists(public_path($truck->documento_unico))) {
                unlink(public_path($truck->documento_unico));
            }

            $file = $request->file('documento_unico');
            $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

            $path = public_path('uploads/trucks/docs');
            if (!file_exists($path)) mkdir($path, 0755, true);

            $file->move($path, $fileName);
            $data['documento_unico'] = 'uploads/trucks/docs/'.$fileName;
        }

        $truck->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Truck updated successfully',
            'data' => $truck
        ]);
    }

    /*
    |-----------------------------------------
    | DELETE TRUCK
    |-----------------------------------------
    */
    public function destroy(Request $request, $id)
    {
        $adminId = auth()->id();

        $truck = Truck::where('id', $id)
            ->where('admin_id', $adminId)
            ->firstOrFail();

        if ($truck->image && file_exists(public_path($truck->image))) {
            unlink(public_path($truck->image));
        }

        if ($truck->documento_unico && file_exists(public_path($truck->documento_unico))) {
            unlink(public_path($truck->documento_unico));
        }

        $truck->delete();

        return response()->json([
            'status' => true,
            'message' => 'Truck deleted successfully'
        ]);
    }


    /*
    |-----------------------------------------
    | ASSIGN DRIVER TO TRUCK
    |-----------------------------------------
    */
    public function assignDriver(Request $request)
    {
        $request->validate([
            'admin_id' => 'required|exists:users,id',
            'truck_id' => 'required|exists:trucks,id',
          'driver_id'=> 'required|exists:drivers,id',
        ]);

        $truck = Truck::where('id', $request->truck_id)
            ->where('admin_id', $request->admin_id)
            ->firstOrFail();

        $truck->driver_id = $request->driver_id;
        $truck->save();

        return response()->json([
            'status' => true,
            'message' => 'Driver assigned successfully',
            'data' => $truck
        ]);
    }

    /*
    |-----------------------------------------
    | SHOW TRUCK
    |-----------------------------------------
    */
    public function show(Request $request, $id)
    {
        $adminId = auth()->id();

        $truck = Truck::where('id', $id)
            ->where('admin_id', $adminId)
            ->firstOrFail();

        return response()->json([
            'status' => true,
            'message' => 'Truck detail fetched',
            'data' => $truck
        ]);
    }
}