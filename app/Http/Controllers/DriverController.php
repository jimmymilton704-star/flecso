<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Mail\DriverWelcomeMail;
use Illuminate\Support\Facades\Mail;

class DriverController extends Controller
{
    private function getActiveSubscription($adminId)
    {
        return Subscription::where('user_id', $adminId)->latest()->first();
    }

    private function checkDriverLimit($adminId)
    {
        $subscription = $this->getActiveSubscription($adminId);

        if (!$subscription) {
            return ['allowed' => false, 'message' => 'No active subscription'];
        }

        $now = Carbon::now();

        if ($subscription->status === 'trial') {
            if ($now->lt($subscription->trial_ends_at)) {
                return ['allowed' => true];
            }
            return ['allowed' => false, 'message' => 'Trial expired'];
        }

        if ($subscription->current_period_end && $now->gt($subscription->current_period_end)) {
            return ['allowed' => false, 'message' => 'Subscription expired'];
        }

        $plan = Plan::find($subscription->plan_id);

        $driverCount = Driver::where('admin_id', $adminId)->count();

        if ($plan && strtolower($plan->name) === 'basic' && $driverCount >= 10) {
            return ['allowed' => false, 'message' => 'Limit reached (10 drivers)'];
        }

        return ['allowed' => true];
    }

    /* LIST */
    public function index()
    {
        $drivers = Driver::where('admin_id', auth()->id())->latest()->paginate(10);;
        return view('drivers.index', compact('drivers'));
    }

    /* CREATE FORM */
    public function create()
    {
        return view('drivers.create');
    }

    /* STORE */
    public function store(Request $request)
    {
        $adminId = auth()->id();

        $request->validate([
            'full_name' => 'required',
            'email' => 'required|email|unique:drivers,email',
            'phone' => 'required',
            'password' => 'required|min:6',
            'status' => 'required'
        ]);

        $limit = $this->checkDriverLimit($adminId);

        if (!$limit['allowed']) {
            return back()->with('error', $limit['message']);
        }

        $plainPassword = $request->password;

        $data = $request->all();
        $data['admin_id'] = $adminId;
        $data['password'] = Hash::make($request->password);

        // Upload helper
        $upload = function ($field, $path) use ($request, &$data) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $name = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path($path), $name);

                $data[$field] = $path . '/' . $name;
            }
        };

        $upload('driver_photo', 'uploads/drivers');
        $upload('license_front', 'uploads/drivers/docs');
        $upload('license_back', 'uploads/drivers/docs');
        $upload('cqc_card', 'uploads/drivers/docs');
        $upload('work_permit_file', 'uploads/drivers/docs');
        $upload('medical_certificate', 'uploads/drivers/docs');

        $driver = Driver::create($data);

        // SEND EMAIL
        Mail::to($driver->email)->send(
            new DriverWelcomeMail($driver, $plainPassword)
        );

        return redirect()
            ->route('drivers.index')
            ->with('success', 'Driver created and credentials email sent.');
    }

    /* EDIT */
    public function edit($id)
    {
        $driver = Driver::where('admin_id', auth()->id())->findOrFail($id);
        return view('drivers.edit', compact('driver'));
    }

    /* UPDATE */
    public function update(Request $request, $id)
    {
        $driver = Driver::where('admin_id', auth()->id())->findOrFail($id);

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

        return redirect()->route('drivers.index')->with('success', 'Driver updated');
    }

    /* DELETE */
    public function destroy($id)
    {
        $driver = Driver::where('admin_id', auth()->id())->findOrFail($id);

        foreach ([
            'driver_photo',
            'license_front',
            'license_back',
            'cqc_card',
            'work_permit_file',
            'medical_certificate'
        ] as $field) {

            if ($driver->$field && file_exists(public_path($driver->$field))) {
                unlink(public_path($driver->$field));
            }
        }

        $driver->delete();

        return back()->with('success', 'Driver deleted');
    }

    /* SHOW */
    public function show($id)
    {
        $driver = Driver::where('admin_id', auth()->id())->findOrFail($id);
        $truck = Truck::where('driver_id', $driver->id)->first();
        return view('drivers.show', compact('driver', 'truck'));
    }
}