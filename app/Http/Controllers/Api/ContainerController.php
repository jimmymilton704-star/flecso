<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Container;
use Illuminate\Http\Request;

class ContainerController extends Controller
{
    /*
    |-----------------------------------------
    | GET ALL CONTAINERS
    |-----------------------------------------
    */
    public function index()
    {
        $containers = Container::where('admin_id', auth()->id())
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Containers fetched successfully',
            'data' => $containers
        ]);
    }

    /*
    |-----------------------------------------
    | STORE / ADD CONTAINER
    |-----------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            // Basic
            'container_id'            => 'required|string',
            'container_license_number'=> 'required|string',
            'container_type'          => 'required|string',
            'status'                  => 'required|string',
            'weight_capacity'         => 'required|numeric',
            'image'                   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            // ISO
            'owner_code'              => 'required|string|size:3',
            'category_identifier'     => 'required|string|size:1',
            'serial_number'           => 'required|string|size:6',
            'check_digit'             => 'required|string|size:1',
            'iso_type_size_code'      => 'required|string|size:4',

            // Technical
            'manufacturer_serial_number' => 'required|string',
            'manufacture_date'           => 'required|date',
            'max_operating_weight'       => 'required|numeric',
            'stacking_weight'            => 'required|numeric',
            'next_examination_date'      => 'required|date',

            // Custom
            'eori_number'       => 'nullable|string',
            'seal_number'       => 'nullable|string',
            'container_status'  => 'required|in:empty,full',
            'owner_lessor'      => 'nullable|string',
        ]);

        $data = $request->all();

        // 🔐 Attach admin automatically
        $data['admin_id'] = auth()->id();

        /*
        |-----------------------------------------
        | IMAGE UPLOAD
        |-----------------------------------------
        */
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $destinationPath = public_path('uploads/containers');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);

            $data['image'] = 'uploads/containers/' . $fileName;
        }

        $container = Container::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Container created successfully',
            'data' => $container
        ]);
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
        |-----------------------------------------
        | IMAGE UPDATE
        |-----------------------------------------
        */
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $destinationPath = public_path('uploads/containers');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);

            $data['image'] = 'uploads/containers/' . $fileName;
        }

        $container->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Container updated successfully',
            'data' => $container
        ]);
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

        return response()->json([
            'status' => true,
            'message' => 'Container deleted successfully'
        ]);
    }

    /*
    |-----------------------------------------
    | SINGLE CONTAINER
    |-----------------------------------------
    */
    public function show($id)
    {
        $container = Container::where('id', $id)
            ->where('admin_id', auth()->id())
            ->firstOrFail();

        return response()->json([
            'status' => true,
            'message' => 'Container fetched successfully',
            'data' => $container
        ]);
    }
}