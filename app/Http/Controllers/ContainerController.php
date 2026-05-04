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
        $containers = Container::where('admin_id', auth()->id())
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
        $request->validate([
            // Basic
            'container_id'             => 'required|string',
            'container_license_number' => 'required|string',
            'container_type'           => 'required|string',
            'status'                   => 'required|string',
            'weight_capacity'          => 'required|numeric',
            'image'                    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            // ISO
            'owner_code'           => 'required|string|size:3',
            'category_identifier'  => 'required|string|size:1',
            'serial_number'        => 'required|string|size:6',
            'check_digit'          => 'required|string|size:1',
            'iso_type_size_code'   => 'required|string|size:4',

            // Technical
            'manufacturer_serial_number' => 'required|string',
            'manufacture_date'           => 'required|date',
            'max_operating_weight'       => 'required|numeric',
            'stacking_weight'            => 'required|numeric',
            'next_examination_date'      => 'required|date',

            // Custom
            'eori_number'      => 'nullable|string',
            'seal_number'      => 'nullable|string',
            'container_status' => 'required|in:empty,full',
            'owner_lessor'     => 'nullable|string',
        ]);

        $data = $request->all();
        $data['admin_id'] = auth()->id();

        /*
        | IMAGE UPLOAD
        */
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

            $path = public_path('uploads/containers');
            if (!file_exists($path)) mkdir($path, 0755, true);

            $file->move($path, $fileName);
            $data['image'] = 'uploads/containers/'.$fileName;
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
            ->where('admin_id', auth()->id())
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
            ->where('admin_id', auth()->id())
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
            $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

            $path = public_path('uploads/containers');
            if (!file_exists($path)) mkdir($path, 0755, true);

            $file->move($path, $fileName);
            $data['image'] = 'uploads/containers/'.$fileName;
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
            ->where('admin_id', auth()->id())
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
            ->where('admin_id', auth()->id())
            ->firstOrFail();

        return view('containers.show', compact('container'));
    }
}