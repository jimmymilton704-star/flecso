<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Mail\DriverWelcomeMail;
use Illuminate\Support\Facades\Mail;

class DriverController extends Controller
{
    /*
    |-----------------------------------------
    | DRIVER LIMIT CHECK
    |-----------------------------------------
    */
    private function checkDriverLimit($adminId)
    {
        $subscription = $this->getActiveSubscription($adminId);

        if (!$subscription) {
            return [
                'allowed' => false,
                'message' => 'No active subscription found'
            ];
        }

        $now = Carbon::now();

        if ($subscription->status === 'trial') {

            if ($now->lt($subscription->trial_ends_at)) {
                return ['allowed' => true];
            }

            return [
                'allowed' => false,
                'message' => 'Trial expired. Please subscribe to continue.'
            ];
        }

        if ($subscription->current_period_end && $now->gt($subscription->current_period_end)) {
            return [
                'allowed' => false,
                'message' => 'Subscription expired'
            ];
        }

        $plan = Plan::find($subscription->plan_id);

        if (!$plan) {
            return [
                'allowed' => false,
                'message' => 'Plan not found'
            ];
        }

        $driverCount = Driver::where('admin_id', $adminId)->count();

        if (strtolower($plan->name) === 'premium') {
            return ['allowed' => true];
        }

        if (strtolower($plan->name) === 'basic') {
            if ($driverCount >= 10) {
                return [
                    'allowed' => false,
                    'message' => 'Basic plan allows maximum 10 drivers. Upgrade required.'
                ];
            }
        }

        return ['allowed' => true];
    }

    private function getActiveSubscription($adminId)
    {
        return Subscription::where('user_id', $adminId)
            ->latest()
            ->first();
    }

    /*
    |-----------------------------------------
    | GET ALL DRIVERS
    |-----------------------------------------
    */
    public function index()
    {
        $adminId = auth()->id();

        $drivers = Driver::where('admin_id', $adminId)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Drivers fetched successfully',
            'data' => $drivers
        ]);
    }

    /*
    |-----------------------------------------
    | STORE DRIVER
    |-----------------------------------------
    */
    public function store(Request $request)
    {
        $adminId = auth()->id();

        $request->validate([
            'full_name' => 'required|string',
            'email'     => 'required|email|unique:drivers,email',
            'phone'     => 'required|string',
            'password'  => 'required|min:6',
            'status'    => 'required|string',
        ]);

        $limit = $this->checkDriverLimit($adminId);

        if (!$limit['allowed']) {
            return response()->json([
                'status' => false,
                'message' => $limit['message']
            ], 403);
        }

        $plainPassword = $request->password;

        $data = $request->all();
        $data['admin_id'] = $adminId;
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('driver_photo')) {
            $file = $request->file('driver_photo');
            $name = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/drivers'), $name);
            $data['driver_photo'] = 'uploads/drivers/'.$name;
        }

        $upload = function ($field) use ($request, &$data) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $name = time().'_'.$file->getClientOriginalName();
                $file->move(public_path('uploads/drivers/docs'), $name);
                $data[$field] = 'uploads/drivers/docs/'.$name;
            }
        };

        $upload('license_front');
        $upload('license_back');
        $upload('cqc_card');
        $upload('work_permit_file');
        $upload('medical_certificate');

        $driver = Driver::create($data);

         Mail::to($driver->email)->send(
            new DriverWelcomeMail($driver, $plainPassword)
        );

        return response()->json([
            'status' => true,
            'message' => 'Driver created successfully',
            'data' => $driver
        ]);
    }

    /*
    |-----------------------------------------
    | UPDATE DRIVER
    |-----------------------------------------
    */
    public function update(Request $request, $id)
    {
        $adminId = auth()->id();

        $driver = Driver::where('id', $id)
            ->where('admin_id', $adminId)
            ->firstOrFail();

        $data = $request->all();

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $upload = function ($field, $path) use ($request, &$data, $driver) {
            if ($request->hasFile($field)) {

                if ($driver->$field && file_exists(public_path($driver->$field))) {
                    unlink(public_path($driver->$field));
                }

                $file = $request->file($field);
                $name = time().'_'.$file->getClientOriginalName();
                $file->move(public_path($path), $name);

                $data[$field] = $path.'/'.$name;
            }
        };

        $upload('driver_photo', 'uploads/drivers');
        $upload('license_front', 'uploads/drivers/docs');
        $upload('license_back', 'uploads/drivers/docs');
        $upload('cqc_card', 'uploads/drivers/docs');
        $upload('work_permit_file', 'uploads/drivers/docs');
        $upload('medical_certificate', 'uploads/drivers/docs');

        $driver->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Driver updated successfully',
            'data' => $driver
        ]);
    }

    /*
    |-----------------------------------------
    | DELETE DRIVER
    |-----------------------------------------
    */
    public function destroy($id)
    {
        $adminId = auth()->id();

        $driver = Driver::where('id', $id)
            ->where('admin_id', $adminId)
            ->firstOrFail();

        $fields = [
            'driver_photo',
            'license_front',
            'license_back',
            'cqc_card',
            'work_permit_file',
            'medical_certificate'
        ];

        foreach ($fields as $field) {
            if ($driver->$field && file_exists(public_path($driver->$field))) {
                unlink(public_path($driver->$field));
            }
        }

        $driver->delete();

        return response()->json([
            'status' => true,
            'message' => 'Driver deleted successfully'
        ]);
    }

    /*
    |-----------------------------------------
    | SHOW DRIVER
    |-----------------------------------------
    */
    public function show($id)
    {
        $adminId = auth()->id();

        $driver = Driver::where('id', $id)
            ->where('admin_id', $adminId)
            ->firstOrFail();

        return response()->json([
            'status' => true,
            'message' => 'Driver fetched successfully',
            'data' => $driver
        ]);
    }

    /*
    |-----------------------------------------
    | UPDATE STATUS
    |-----------------------------------------
    */
    public function updateStatus(Request $request)
    {
        $adminId = auth()->id();

        $request->validate([
            'driver_id'=> 'required|exists:drivers,id',
            'status'   => 'required|string',
        ]);

        $driver = Driver::where('id', $request->driver_id)
            ->where('admin_id', $adminId)
            ->firstOrFail();

        $driver->update([
            'status' => $request->status
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Driver status updated successfully',
            'data' => $driver
        ]);
    }
}