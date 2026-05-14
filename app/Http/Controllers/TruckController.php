<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use App\Models\Driver;
use App\Models\TruckHealthLog;
use App\Models\TruckMaintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TruckController extends Controller
{
    /*
    |-----------------------------------------
    | INDEX (LIST)
    |-----------------------------------------
    */
    public function index()
    {
        $adminId = auth()->id();

        $trucks = Truck::where('admin_id', $adminId)
            ->latest()
            ->paginate(10);

        return view('trucks.index', compact('trucks'));
    }

    /*
    |-----------------------------------------
    | CREATE FORM
    |-----------------------------------------
    */
    public function create()
    {
        $drivers = Driver::where('admin_id', auth()->id())->get();

        return view('trucks.create', compact('drivers'));
    }

    /*
    |-----------------------------------------
    | STORE
    |-----------------------------------------
    */
    public function store(Request $request)
    {
        $adminId = auth()->id();

        $request->validate([
            'truck_number' => 'required',
            'truck_license_number' => 'required',
            'capacity_tons' => 'required|numeric',
            'truck_type_category' => 'required',
            'type' => 'required',
            'status' => 'required',

            'image' => 'nullable|image',
            'documento_unico' => 'nullable|file',

            'license_plate_number' => 'required',
            'vin_number' => 'nullable',
            'first_registration_date' => 'nullable|date',
            'usage_type' => 'nullable',

            'vehicle_category' => 'nullable',
            'gvw_kg' => 'nullable|numeric',
            'payload_capacity_kg' => 'nullable|numeric',
            'number_of_axles' => 'nullable|integer',
            'engine_class' => 'nullable',
            'fuel_type' => 'nullable',

            'next_inspection_date' => 'nullable|date',
            'insurance_policy_number' => 'nullable',
            'insurance_expiry_date' => 'nullable|date',
            'tachograph_calibration_expiry' => 'nullable|date',
            'bollo_expiry_date' => 'nullable|date',
            'current_km' => 'required',
            'estimate_km' => 'required',
        ]);

        $data = $request->except(['current_km', 'estimate_km', 'last_maintenance_date']);
        $data['admin_id'] = $adminId;

        /*
        | IMAGE
        */
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/trucks'), $name);
            $data['image'] = 'uploads/trucks/' . $name;
        }

        /*
        | DOC
        */
        if ($request->hasFile('documento_unico')) {
            $file = $request->file('documento_unico');
            $name = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/trucks/docs'), $name);
            $data['documento_unico'] = 'uploads/trucks/docs/' . $name;
        }

        $truck = Truck::create($data);


        TruckMaintenance::create([
            'truck_id' => $truck->id,
            'next_due_km' => $request->estimate_km,
            'last_service_km' => $request->current_km,
            'type' => 'general',
        ]);

        return redirect()->route('trucks.index')->with('success', 'Truck created');
    }

    /*
    |-----------------------------------------
    | SHOW
    |-----------------------------------------
    */
    public function show($id)
    {
        $truck = Truck::where('admin_id', auth()->id())->findOrFail($id);
        $driver = Driver::where('admin_id', auth()->id())->get();


        return view('trucks.show', compact('truck', 'driver'));
    }

    /*
    |-----------------------------------------
    | EDIT
    |-----------------------------------------
    */
    public function edit($id)
    {
        $truck = Truck::where('admin_id', auth()->id())->findOrFail($id);
        $drivers = Driver::where('admin_id', auth()->id())->get();

        return view('trucks.edit', compact('truck', 'drivers'));
    }

    /*
    |-----------------------------------------
    | UPDATE
    |-----------------------------------------
    */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $truck = Truck::where('admin_id', auth()->id())->findOrFail($id);

        $data = $request->except(['current_km', 'estimate_km',]);

        if ($request->hasFile('image')) {
            if ($truck->image && file_exists(public_path($truck->image))) {
                unlink(public_path($truck->image));
            }

            $file = $request->file('image');
            $name = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/trucks'), $name);
            $data['image'] = 'uploads/trucks/' . $name;
        }

        if ($request->hasFile('documento_unico')) {
            if ($truck->documento_unico && file_exists(public_path($truck->documento_unico))) {
                unlink(public_path($truck->documento_unico));
            }

            $file = $request->file('documento_unico');
            $name = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/trucks/docs'), $name);
            $data['documento_unico'] = 'uploads/trucks/docs/' . $name;
        }

        $truck->update($data);

        TruckMaintenance::updateOrCreate([
            'truck_id' => $truck->id,
            'type' => 'general',
        ], [
            'next_due_km' => $request->estimate_km,
            'last_service_km' => $request->current_km,

        ]);


        return redirect()->route('trucks.index')->with('success', 'Truck updated');
    }

    /*
    |-----------------------------------------
    | DELETE
    |-----------------------------------------
    */
    public function destroy($id)
    {
        $truck = Truck::where('admin_id', auth()->id())->findOrFail($id);

        if ($truck->image && file_exists(public_path($truck->image))) {
            unlink(public_path($truck->image));
        }

        if ($truck->documento_unico && file_exists(public_path($truck->documento_unico))) {
            unlink(public_path($truck->documento_unico));
        }

        $truck->delete();

        return redirect()->route('trucks.index')->with('success', 'Truck deleted');
    }

    /*
    |-----------------------------------------
    | ASSIGN DRIVER
    |-----------------------------------------
    */
    public function assignDriver(Request $request)
    {
        $request->validate([
            'truck_id' => 'required|exists:trucks,id',
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $truck = Truck::where('id', $request->truck_id)
            ->where('admin_id', auth()->id())
            ->firstOrFail();

        $truck->driver_id = $request->driver_id;
        $truck->save();

        return back()->with('success', 'Driver assigned');
    }

    /*
|-----------------------------------------
| IMPORT CSV
|-----------------------------------------
*/
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt'
        ]);

        $file = fopen($request->file('csv_file')->getRealPath(), 'r');

        $header = fgetcsv($file);

        $adminId = auth()->id();

        DB::beginTransaction();

        try {

            while (($row = fgetcsv($file)) !== false) {

                $data = array_combine($header, $row);

                
                /*
                | DRIVER LOOKUP
                */
                $driverId = null;

                if (!empty($data['driver_email'])) {

                    $driver = Driver::where('admin_id', $adminId)
                        ->where('email', $data['driver_email'])
                        ->first();

                    if ($driver) {
                        $driverId = $driver->id;
                    }
                }

                /*
                | CREATE TRUCK
                */
                $truck = Truck::create([
                    'admin_id' => $adminId,

                    'truck_number' => $data['truck_number'],
                    'truck_license_number' => $data['truck_license_number'],
                    'capacity_tons' => $data['capacity_tons'],
                    'truck_type_category' => $data['truck_type_category'],
                    'type' => $data['type'],
                    'status' => $data['status'],

                    'license_plate_number' => $data['license_plate_number'],
                    'vin_number' => $data['vin_number'],
                    'first_registration_date' => $data['first_registration_date'],
                    'usage_type' => $data['usage_type'],

                    'vehicle_category' => $data['vehicle_category'],
                    'gvw_kg' => $data['gvw_kg'],
                    'payload_capacity_kg' => $data['payload_capacity_kg'],
                    'number_of_axles' => $data['number_of_axles'],
                    'engine_class' => $data['engine_class'],
                    'fuel_type' => $data['fuel_type'],

                    'next_inspection_date' => $data['next_inspection_date'],
                    'insurance_policy_number' => $data['insurance_policy_number'],
                    'insurance_expiry_date' => $data['insurance_expiry_date'],
                    'tachograph_calibration_expiry' => $data['tachograph_calibration_expiry'],
                    'bollo_expiry_date' => $data['bollo_expiry_date'],

                    'driver_id' => $driverId,
                ]);

                /*
                | MAINTENANCE RECORD
                */
                TruckMaintenance::create([
                    'truck_id' => $truck->id,
                    'next_due_km' => $data['estimate_km'],
                    'last_service_km' => $data['current_km'],
                    'type' => 'general',
                ]);
            }

            fclose($file);

            DB::commit();

            return back()->with('success', 'Trucks imported successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }


}