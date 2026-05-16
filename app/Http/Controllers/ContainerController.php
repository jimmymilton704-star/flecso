<?php

namespace App\Http\Controllers;

use App\Models\Container;
use Illuminate\Http\Request;

class ContainerController extends Controller
{
    /*
    |-----------------------------------------
    | LIST CONTAINERS
    |-----------------------------------------
    */
    public function index()
    {
        $containers = Container::where('admin_id', auth()->user()->parent_id ?: auth()->id())
            ->latest()
            ->get();

        return view('containers.index', compact('containers'));
    }

    /*
    |-----------------------------------------
    | CREATE FORM
    |-----------------------------------------
    */
    public function create()
    {
        return view('containers.create');
    }

    /*
    |-----------------------------------------
    | STORE CONTAINER
    |-----------------------------------------
    */
    public function store(Request $request)
    {
        // dd($request->all());
        $validation = $request->validate([
            // Basic
            'container_id' => 'required|string',
            'container_license_number' => 'required|string',
            'container_type' => 'required|string',
            'status' => 'required|string',
            'weight_capacity' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            // ISO
            'owner_code' => 'nullable|string|size:3',
            'category_identifier' => 'nullable|string|size:1',
            'serial_number' => 'nullable|string|size:6',
            'check_digit' => 'nullable|string|size:1',
            'iso_type_size_code' => 'nullable|string|size:4',

            // Technical
            'manufacturer_serial_number' => 'nullable|string',
            'manufacture_date' => 'nullable|date',
            'max_operating_weight' => 'nullable|numeric',
            'stacking_weight' => 'nullable|numeric',
            'next_examination_date' => 'nullable|date',

            // Custom
            'eori_number' => 'nullable|string',
            'seal_number' => 'nullable|string',
            'container_status' => 'required|in:empty,full',
            'owner_lessor' => 'nullable|string',
        ]);

        // dd($validation);
        $data = $request->all();

        $data['admin_id'] = auth()->user()->parent_id ?: auth()->id();

        /*
        | IMAGE UPLOAD
        */
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $path = public_path('uploads/containers');
            if (!file_exists($path))
                mkdir($path, 0755, true);

            $file->move($path, $fileName);
            $data['image'] = 'uploads/containers/' . $fileName;
        }


        Container::create($data);

        return redirect()->route('containers.index')
            ->with('success', 'Container created successfully');
    }

    /*
    |-----------------------------------------
    | EDIT FORM
    |-----------------------------------------
    */
    public function edit($id)
    {
        $container = Container::where('id', $id)
            ->where('admin_id', auth()->user()->parent_id ?: auth()->id())
            ->firstOrFail();

        return view('containers.edit', compact('container'));
    }

    /*
    |-----------------------------------------
    | UPDATE CONTAINER
    |-----------------------------------------
    */
    public function update(Request $request, $id)
    {
        $container = Container::where('id', $id)
            ->where('admin_id', auth()->user()->parent_id ?: auth()->id())
            ->firstOrFail();

        $data = $request->all();

        /*
        | IMAGE UPDATE
        */
        if ($request->hasFile('image')) {

            if ($container->image && file_exists(public_path($container->image))) {
                unlink(public_path($container->image));
            }

            $file = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $path = public_path('uploads/containers');
            if (!file_exists($path))
                mkdir($path, 0755, true);

            $file->move($path, $fileName);
            $data['image'] = 'uploads/containers/' . $fileName;
        }

        $container->update($data);

        return redirect()->route('containers.index')
            ->with('success', 'Container updated successfully');
    }

    /*
    |-----------------------------------------
    | DELETE CONTAINER
    |-----------------------------------------
    */
    public function destroy($id)
    {
        $container = Container::where('id', $id)
            ->where('admin_id', auth()->user()->parent_id ?: auth()->id())
            ->firstOrFail();

        if ($container->image && file_exists(public_path($container->image))) {
            unlink(public_path($container->image));
        }

        $container->delete();

        return redirect()->route('containers.index')
            ->with('success', 'Container deleted successfully');
    }

    /*
    |-----------------------------------------
    | SHOW SINGLE CONTAINER
    |-----------------------------------------
    */
    public function show($id)
    {
        $container = Container::where('id', $id)
            ->where('admin_id', auth()->user()->parent_id ?: auth()->id())
            ->firstOrFail();

        return view('containers.show', compact('container'));
    }

    /*
|-----------------------------------------
| IMPORT CONTAINERS CSV
|-----------------------------------------
*/
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        $file = fopen($request->file('csv_file')->getRealPath(), 'r');

        $header = fgetcsv($file);

        $adminId = auth()->user()->parent_id ?: auth()->id();

        $count = 0;

        while (($row = fgetcsv($file)) !== false) {

            $data = array_combine($header, $row);

            Container::create([
                'admin_id' => $adminId,

                // BASIC
                'container_id' => $data['container_id'],
                'container_license_number' => $data['container_license_number'],
                'container_type' => $data['container_type'],
                'status' => $data['status'],
                'weight_capacity' => $data['weight_capacity'],
                'container_status' => $data['container_status'],

                // ISO
                'owner_code' => $data['owner_code'],
                'category_identifier' => $data['category_identifier'],
                'serial_number' => $data['serial_number'],
                'check_digit' => $data['check_digit'],
                'iso_type_size_code' => $data['iso_type_size_code'],

                // TECHNICAL
                'manufacturer_serial_number' => $data['manufacturer_serial_number'],
                'manufacture_date' => $data['manufacture_date'],
                'max_operating_weight' => $data['max_operating_weight'],
                'stacking_weight' => $data['stacking_weight'],
                'next_examination_date' => $data['next_examination_date'],

                // EXTRA
                'eori_number' => $data['eori_number'] ?? null,
                'seal_number' => $data['seal_number'] ?? null,
                'owner_lessor' => $data['owner_lessor'] ?? null,
            ]);

            $count++;
        }

        fclose($file);

        return redirect()
            ->route('containers.index')
            ->with('success', $count . ' containers imported successfully.');
    }
}