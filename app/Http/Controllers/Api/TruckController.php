<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Truck;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\TruckHealthLog;
use App\Models\TruckMaintenance;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
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
            'truck_number' => 'required|string',
            'truck_license_number' => 'required|string',
            'capacity_tons' => 'required|numeric',
            'truck_type_category' => 'required|string',
            'type' => 'required|string',
            'status' => 'required|string',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'license_plate_number' => 'required|string',
            'vin_number' => 'nullable|string',
            'first_registration_date' => 'nullable|date',
            'usage_type' => 'nullable|string',
            'documento_unico' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',

            'vehicle_category' => 'nullable|string',
            'gvw_kg' => 'nullable|numeric',
            'payload_capacity_kg' => 'nullable|numeric',
            'number_of_axles' => 'nullable|integer',
            'engine_class' => 'nullable|string',
            'fuel_type' => 'nullable|string',

            'next_inspection_date' => 'nullable|date',
            'insurance_policy_number' => 'nullable|string',
            'insurance_expiry_date' => 'nullable|date',
            'tachograph_calibration_expiry' => 'nullable|date',
            'bollo_expiry_date' => 'nullable|date',
        ]);

        $data = $request->all();
        $data['admin_id'] = $adminId;

        /*
        | IMAGE UPLOAD
        */
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $path = public_path('uploads/trucks');
            if (!file_exists($path))
                mkdir($path, 0755, true);

            $file->move($path, $fileName);
            $data['image'] = 'uploads/trucks/' . $fileName;
        }

        /*
        | DOCUMENT UPLOAD
        */
        if ($request->hasFile('documento_unico')) {
            $file = $request->file('documento_unico');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $path = public_path('uploads/trucks/docs');
            if (!file_exists($path))
                mkdir($path, 0755, true);

            $file->move($path, $fileName);
            $data['documento_unico'] = 'uploads/trucks/docs/' . $fileName;
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
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $path = public_path('uploads/trucks');
            if (!file_exists($path))
                mkdir($path, 0755, true);

            $file->move($path, $fileName);
            $data['image'] = 'uploads/trucks/' . $fileName;
        }

        if ($request->hasFile('documento_unico')) {
            if ($truck->documento_unico && file_exists(public_path($truck->documento_unico))) {
                unlink(public_path($truck->documento_unico));
            }

            $file = $request->file('documento_unico');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $path = public_path('uploads/trucks/docs');
            if (!file_exists($path))
                mkdir($path, 0755, true);

            $file->move($path, $fileName);
            $data['documento_unico'] = 'uploads/trucks/docs/' . $fileName;
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
            'driver_id' => 'required|exists:drivers,id',
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

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt'
        ]);

        /*
        |--------------------------------------------
        | ADMIN AUTH ONLY
        |--------------------------------------------
        */
        $admin = auth()->user();

        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $file = fopen($request->file('csv_file')->getRealPath(), 'r');

        $header = fgetcsv($file);

        $count = 0;

        DB::beginTransaction();

        try {

            while (($row = fgetcsv($file)) !== false) {

                $data = array_combine($header, $row);

                /*
                |--------------------------------------------
                | DRIVER LOOKUP (optional assignment)
                |--------------------------------------------
                */
                $driverId = null;

                if (!empty($data['driver_email'])) {

                    $driver = Driver::where('admin_id', $admin->id)
                        ->where('email', $data['driver_email'])
                        ->first();

                    if ($driver) {
                        $driverId = $driver->id;
                    }
                }

                /*
                |--------------------------------------------
                | CREATE TRUCK
                |--------------------------------------------
                */
                $truck = Truck::create([
                    'admin_id' => $admin->id,

                    'truck_number' => $data['truck_number'] ?? null,
                    'truck_license_number' => $data['truck_license_number'] ?? null,
                    'capacity_tons' => $data['capacity_tons'] ?? null,
                    'truck_type_category' => $data['truck_type_category'] ?? null,
                    'type' => $data['type'] ?? null,
                    'status' => $data['status'] ?? null,

                    'license_plate_number' => $data['license_plate_number'] ?? null,
                    'vin_number' => $data['vin_number'] ?? null,
                    'first_registration_date' => $data['first_registration_date'] ?? null,
                    'usage_type' => $data['usage_type'] ?? null,

                    'vehicle_category' => $data['vehicle_category'] ?? null,
                    'gvw_kg' => $data['gvw_kg'] ?? null,
                    'payload_capacity_kg' => $data['payload_capacity_kg'] ?? null,
                    'number_of_axles' => $data['number_of_axles'] ?? null,
                    'engine_class' => $data['engine_class'] ?? null,
                    'fuel_type' => $data['fuel_type'] ?? null,

                    'next_inspection_date' => $data['next_inspection_date'] ?? null,
                    'insurance_policy_number' => $data['insurance_policy_number'] ?? null,
                    'insurance_expiry_date' => $data['insurance_expiry_date'] ?? null,
                    'tachograph_calibration_expiry' => $data['tachograph_calibration_expiry'] ?? null,
                    'bollo_expiry_date' => $data['bollo_expiry_date'] ?? null,

                    'driver_id' => $driverId,
                ]);

                /*
                |--------------------------------------------
                | MAINTENANCE RECORD
                |--------------------------------------------
                */
                TruckMaintenance::create([
                    'truck_id' => $truck->id,
                    'next_due_km' => $data['estimate_km'] ?? null,
                    'last_service_km' => $data['current_km'] ?? null,
                    'type' => 'general',
                ]);

                $count++;
            }

            fclose($file);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Trucks imported successfully',
                'imported' => $count
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}