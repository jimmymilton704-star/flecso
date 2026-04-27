<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\Driver;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register API user
     */

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|string|max:20',
            'password' => 'required|min:8|confirmed',
        ]);

        // 1. Create User (Admin)
        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'password'          => Hash::make($request->password),
            'role'              => 'admin',
            'profile_completed' => false,
        ]);

        // 2. Auto create 14-day TRIAL subscription
        $trialStart = now();
        $trialEnd   = now()->addDays(14);

        Subscription::create([
            'user_id' => $user->id,

            // Trial system
            'status' => 'trial',
            'trial_ends_at' => $trialEnd,

            // Billing cycle (same as trial initially)
            'current_period_start' => $trialStart,
            'current_period_end'   => $trialEnd,

            // Stripe fields (empty for now)
            'stripe_customer_id'    => null,
            'stripe_subscription_id'=> null,
            'stripe_price_id'       => null,

            // Add-ons
            'extra_drivers' => 0,
            'extra_cost'    => 0,
        ]);

        // 3. Create token
        $token = $user->createToken('api-token')->plainTextToken;

        // 4. Response
        return response()->json([
            'status'  => true,
            'message' => 'Registration successful',

            'token' => $token,

            'profile_completed' => false,

            'trial' => [
                'start' => $trialStart,
                'end'   => $trialEnd,
                'days_left' => 14
            ],

            'data' => $user,
        ], 200);
    }
    /**
     * Login API driver
     */

    public function registerDriver(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $image      = $request->file('avatar');
            $imageName  = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $destinationPath = public_path('uploads/images');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $imageName);

            $avatarPath = 'uploads/images/' . $imageName;
        }


        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'avatar'       => $avatarPath ?? null,
            'role'         => 'driver',
            'parent_id'    => auth()->user()->id
        ]);

        return response()->json([
            'message' => 'Registration successful',
            'data'    => $user,
        ], 200);
    }

    /**
     * Complete Profile Step 1
     */
    public function completeProfileStep1(Request $request)
    {
        $request->validate([
            'admin_id'          => 'required|exists:users,id',
            'company_legal_name' => 'required|string',
            'company_type'       => 'required|string',
            'vat_number'         => 'required|digits:11',
            'fiscal_code'        => 'required|string',
            'rea_number'         => 'required|string',
        ]);

        $user = $request->user(); 

        if ($user->id !== $request->user()->id) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        $user->update([
            'company_legal_name' => $request->company_legal_name,
            'company_type'       => $request->company_type,
            'vat_number'         => $request->vat_number,
            'fiscal_code'        => $request->fiscal_code,
            'rea_number'         => $request->rea_number,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Step 1 completed',
            'data' => $user
        ]);
    }
    
    /**
     * Complete Profile Step 2
     */
    public function completeProfileStep2(Request $request)
    {
        $request->validate([
            'admin_id'          => 'required|exists:users,id',
            'pec_email'          => 'required|email',
            'sdi_code'           => 'required|string|size:7',
            'registered_address' => 'required|string',
            'city'               => 'required|string',
            'province'           => 'required|string|size:2',
            'zip_code'           => 'required|string',
        ]);

       $user = $request->user(); 

        $user->update([
            'pec_email'          => $request->pec_email,
            'sdi_code'           => $request->sdi_code,
            'registered_address' => $request->registered_address,
            'city'               => $request->city,
            'province'           => $request->province,
            'zip_code'           => $request->zip_code,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Step 2 completed',
            'data' => $user
        ]);
    }

    /**
     * Complete Profile Step 3
     */

    public function completeProfileStep3(Request $request)
    {
        $request->validate([
            'admin_id'          => 'required|exists:users,id',
            'ren_number'           => 'required|string',
            'eu_license_number'    => 'nullable|string',
            'fleet_trucks'         => 'required|integer|min:0',
            'fleet_vans'           => 'required|integer|min:0',
            'fleet_containers'     => 'required|integer|min:0',
            'insurance_policy_number' => 'required|string',
        ]);

        $user = $request->user();

        $user->update([
            'ren_number'            => $request->ren_number,
            'eu_license_number'     => $request->eu_license_number,
            'fleet_trucks'          => $request->fleet_trucks,
            'fleet_vans'            => $request->fleet_vans,
            'fleet_containers'      => $request->fleet_containers,
            'insurance_policy_number'=> $request->insurance_policy_number,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Step 3 completed',
            'data' => $user
        ]);
    }

    /**
     * Complete Profile Step 4
     */
    public function completeProfileStep4(Request $request)
    {
        $request->validate([
            'admin_id'          => 'required|exists:users,id',
            'rep_full_name'    => 'required|string',
            'rep_position'     => 'required|string',
            'rep_fiscal_code'  => 'required|string|size:16',
            'rep_document'     => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $user = $request->user();

        $documentPath = null;

        if ($request->hasFile('rep_document')) {
            $file = $request->file('rep_document');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $destinationPath = public_path('uploads/documents');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);

            $documentPath = 'uploads/documents/' . $fileName;
        }

        $user->update([
            'rep_full_name'     => $request->rep_full_name,
            'rep_position'      => $request->rep_position,
            'rep_fiscal_code'   => $request->rep_fiscal_code,
            'rep_document'      => $documentPath,
            'profile_completed' => true,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Profile completed successfully',
            'profile_completed' => true,
            'data' => $user
        ]);
    }


    /**
     * Track Profile Completion and Get Current Step
     */
    public function profile(Request $request)
    {
        $user = User::findOrFail($request->admin_id);

        $currentStep = 1;

        if (
            $user->company_legal_name &&
            $user->company_type &&
            $user->vat_number &&
            $user->fiscal_code &&
            $user->rea_number
        ) {
            $currentStep = 2;
        }

        if (
            $user->pec_email &&
            $user->sdi_code &&
            $user->registered_address &&
            $user->city &&
            $user->province &&
            $user->zip_code
        ) {
            $currentStep = 3;
        }

        if (
            $user->ren_number &&
            $user->fleet_trucks !== null &&
            $user->fleet_vans !== null &&
            $user->fleet_containers !== null &&
            $user->insurance_policy_number
        ) {
            $currentStep = 4;
        }

        if (
            $user->rep_full_name &&
            $user->rep_position &&
            $user->rep_fiscal_code &&
            $user->rep_document
        ) {
            $currentStep = 5; // completed
        }

        return response()->json([
            'status' => true,
            'message' => 'Profile fetched successfully',

            'profile_completed' => (bool) $user->profile_completed,
            'current_step' => $currentStep,

            'data' => $user
        ]);
    }

    /**
     * Login API user
     */

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);


        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        if ($user->role !== 'admin') {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthorized access'
            ], 403);
        }


        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Login successful',
            'token'   => $token,
            'profile_completed' => (bool) $user->profile_completed,
            'data'    => $user,
        ]);
    }

    /**
     * Logout API user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ], 200);
    }

    /**
     * Forgot Password API user
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'status'  => true,
                'message' => 'Password reset link sent to your email'
            ]);
        }

        return response()->json([
            'status'  => false,
            'message' => __($status)
        ], 500);
    }

    public function driverLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $driver = Driver::where('email', $request->email)->first();

        if (!$driver || !Hash::check($request->password, $driver->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Check driver status
        if ($driver->status !== 'active') {
            return response()->json([
                'status' => false,
                'message' => 'Driver is not active'
            ], 403);
        }

        // Create token
        $token = $driver->createToken('driver-token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Driver login successful',
            'token'   => $token,
            'data'    => $driver
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'status' => true,
            'data'   => $request->user()
        ]);
    }
}
