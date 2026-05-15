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


    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        $adminId = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | OPEN CSV
        |--------------------------------------------------------------------------
        */
        $file = fopen(
            $request->file('csv_file')->getRealPath(),
            'r'
        );

        /*
        |--------------------------------------------------------------------------
        | GET HEADER
        |--------------------------------------------------------------------------
        */
        $header = fgetcsv($file);

        if (!$header) {

            return response()->json([
                'status' => false,
                'message' => 'Invalid CSV file'
            ], 400);
        }

        $imported = 0;
        $skipped = 0;
        $errors = [];

        /*
        |--------------------------------------------------------------------------
        | LOOP CSV ROWS
        |--------------------------------------------------------------------------
        */
        while (($row = fgetcsv($file)) !== false) {

            try {

                $data = array_combine($header, $row);

                /*
                |--------------------------------------------------------------------------
                | SKIP EMPTY CONTAINER ID
                |--------------------------------------------------------------------------
                */
                if (empty($data['container_id'])) {

                    $skipped++;
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | SKIP DUPLICATE CONTAINER
                |--------------------------------------------------------------------------
                */
                if (
                    Container::where('container_id', $data['container_id'])
                        ->where('admin_id', $adminId)
                        ->exists()
                ) {

                    $skipped++;
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | CREATE CONTAINER
                |--------------------------------------------------------------------------
                */
                Container::create([

                    'admin_id' => $adminId,

                    /*
                    |--------------------------------------------------------------------------
                    | BASIC
                    |--------------------------------------------------------------------------
                    */
                    'container_id' =>
                        $data['container_id'] ?? null,

                    'container_license_number' =>
                        $data['container_license_number'] ?? null,

                    'container_type' =>
                        $data['container_type'] ?? null,

                    'status' =>
                        $data['status'] ?? 'active',

                    'weight_capacity' =>
                        $data['weight_capacity'] ?? null,

                    'container_status' =>
                        $data['container_status'] ?? null,

                    /*
                    |--------------------------------------------------------------------------
                    | ISO
                    |--------------------------------------------------------------------------
                    */
                    'owner_code' =>
                        $data['owner_code'] ?? null,

                    'category_identifier' =>
                        $data['category_identifier'] ?? null,

                    'serial_number' =>
                        $data['serial_number'] ?? null,

                    'check_digit' =>
                        $data['check_digit'] ?? null,

                    'iso_type_size_code' =>
                        $data['iso_type_size_code'] ?? null,

                    /*
                    |--------------------------------------------------------------------------
                    | TECHNICAL
                    |--------------------------------------------------------------------------
                    */
                    'manufacturer_serial_number' =>
                        $data['manufacturer_serial_number'] ?? null,

                    'manufacture_date' =>
                        $data['manufacture_date'] ?? null,

                    'max_operating_weight' =>
                        $data['max_operating_weight'] ?? null,

                    'stacking_weight' =>
                        $data['stacking_weight'] ?? null,

                    'next_examination_date' =>
                        $data['next_examination_date'] ?? null,

                    /*
                    |--------------------------------------------------------------------------
                    | EXTRA
                    |--------------------------------------------------------------------------
                    */
                    'eori_number' =>
                        $data['eori_number'] ?? null,

                    'seal_number' =>
                        $data['seal_number'] ?? null,

                    'owner_lessor' =>
                        $data['owner_lessor'] ?? null,
                ]);

                $imported++;

            } catch (\Exception $e) {

                $errors[] = $e->getMessage();
            }
        }

        fclose($file);

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */
        return response()->json([
            'status' => true,
            'message' => 'Containers imported successfully',
            'imported' => $imported,
            'skipped' => $skipped,
            'errors' => $errors,
        ]);
    }
}