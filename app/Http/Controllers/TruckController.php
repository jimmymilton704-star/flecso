<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use App\Models\Driver;
use Illuminate\Http\Request;

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
            'vin_number' => 'required',
            'first_registration_date' => 'required|date',
            'usage_type' => 'required',

            'vehicle_category' => 'required',
            'gvw_kg' => 'required|numeric',
            'payload_capacity_kg' => 'required|numeric',
            'number_of_axles' => 'required|integer',
            'engine_class' => 'required',
            'fuel_type' => 'required',

            'next_inspection_date' => 'required|date',
            'insurance_policy_number' => 'required',
            'insurance_expiry_date' => 'required|date',
            'tachograph_calibration_expiry' => 'required|date',
            'bollo_expiry_date' => 'required|date',
        ]);

        $data = $request->all();
        $data['admin_id'] = $adminId;

        /*
        | IMAGE
        */
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/trucks'), $name);
            $data['image'] = 'uploads/trucks/'.$name;
        }

        /*
        | DOC
        */
        if ($request->hasFile('documento_unico')) {
            $file = $request->file('documento_unico');
            $name = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/trucks/docs'), $name);
            $data['documento_unico'] = 'uploads/trucks/docs/'.$name;
        }

        Truck::create($data);

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

        return view('trucks.show', compact('truck'));
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
        $truck = Truck::where('admin_id', auth()->id())->findOrFail($id);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($truck->image && file_exists(public_path($truck->image))) {
                unlink(public_path($truck->image));
            }

            $file = $request->file('image');
            $name = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/trucks'), $name);
            $data['image'] = 'uploads/trucks/'.$name;
        }

        if ($request->hasFile('documento_unico')) {
            if ($truck->documento_unico && file_exists(public_path($truck->documento_unico))) {
                unlink(public_path($truck->documento_unico));
            }

            $file = $request->file('documento_unico');
            $name = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/trucks/docs'), $name);
            $data['documento_unico'] = 'uploads/trucks/docs/'.$name;
        }

        $truck->update($data);

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
            'driver_id'=> 'required|exists:drivers,id',
        ]);

        $truck = Truck::where('id', $request->truck_id)
            ->where('admin_id', auth()->id())
            ->firstOrFail();

        $truck->driver_id = $request->driver_id;
        $truck->save();

        return back()->with('success', 'Driver assigned');
    }
}