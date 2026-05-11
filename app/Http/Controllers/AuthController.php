<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | VIEWS
    |--------------------------------------------------------------------------
    */

    public function showRegister()
    {
        return view('auth.register');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function showResetPassword($token)
    {
        return view('auth.reset-password', compact('token'));
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTER (ADMIN)
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'profile_completed' => false,
        ]);

        // Trial subscription
        $trialStart = now();
        $trialEnd = now()->addDays(14);

        Subscription::create([
            'user_id' => $user->id,
            'status' => 'trial',
            'trial_ends_at' => $trialEnd,
            'current_period_start' => $trialStart,
            'current_period_end' => $trialEnd,
            'stripe_customer_id' => null,
            'stripe_subscription_id' => null,
            'stripe_price_id' => null,
            'extra_drivers' => 0,
            'extra_cost' => 0,
        ]);

        // Login user
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registration successful! Trial started.');
    }


    /*
    |--------------------------------------------------------------------------
    | LOGIN (ADMIN)
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }

        if ($user->role !== 'admin') {
            return back()->withErrors(['email' => 'Unauthorized access'])->withInput();
        }

        Auth::login($user);

        if (!$user->profile_completed) {

            return redirect()
                ->route('profile.step1')
                ->with(
                    'error',
                    'Please complete your profile first.'
                );
        }

        return redirect()
            ->route('dashboard')
            ->with('success', 'Login successful');
    }


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logged out successfully');
    }


    /*
    |--------------------------------------------------------------------------
    | DRIVER REGISTER
    |--------------------------------------------------------------------------
    */

    public function registerDriver(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $avatarPath = null;

        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $destinationPath = public_path('uploads/images');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $imageName);

            $avatarPath = 'uploads/images/' . $imageName;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => $avatarPath,
            'role' => 'driver',
            'parent_id' => Auth::id()
        ]);

        return back()->with('success', 'Driver registered successfully');
    }


    /*
    |--------------------------------------------------------------------------
    | PROFILE STEPS (1 → 4)
    |--------------------------------------------------------------------------
    */

    public function completeProfileStep1(Request $request)
    {
        $request->validate([
            'company_legal_name' => 'required|string',
            'company_type' => 'required|string',
            'vat_number' => 'required|digits:11',
            'fiscal_code' => 'required|string',
            'rea_number' => 'required|string',
        ]);

        $user = Auth::user();

        $user->update($request->only([
            'company_legal_name',
            'company_type',
            'vat_number',
            'fiscal_code',
            'rea_number'
        ]));

        return redirect()
            ->route('profile.step2')
            ->with('success', 'Step 1 completed');
    }


    public function completeProfileStep2(Request $request)
    {
        $request->validate([
            'pec_email' => 'required|email',
            'sdi_code' => 'required|string|size:7',
            'registered_address' => 'required|string',
            'city' => 'required|string',
            'province' => 'required|string|size:2',
            'zip_code' => 'required|string',
        ]);

        Auth::user()->update($request->only([
            'pec_email',
            'sdi_code',
            'registered_address',
            'city',
            'province',
            'zip_code'
        ]));

        return redirect()
            ->route('profile.step3')
            ->with('success', 'Step 2 completed');
    }


    public function completeProfileStep3(Request $request)
    {
        $request->validate([
            'ren_number' => 'required|string',
            'eu_license_number' => 'nullable|string',
            'fleet_trucks' => 'required|integer|min:0',
            'fleet_vans' => 'required|integer|min:0',
            'fleet_containers' => 'required|integer|min:0',
            'insurance_policy_number' => 'required|string',
        ]);

        Auth::user()->update($request->only([
            'ren_number',
            'eu_license_number',
            'fleet_trucks',
            'fleet_vans',
            'fleet_containers',
            'insurance_policy_number'
        ]));

        return redirect()
            ->route('profile.step4')
            ->with('success', 'Step 3 completed');
    }


    public function completeProfileStep4(Request $request)
    {
        $request->validate([
            'rep_full_name' => 'required|string',
            'rep_position' => 'required|string',
            'rep_fiscal_code' => 'required|string|size:16',
            'rep_document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $user = Auth::user();

        if ($request->hasFile('rep_document')) {
            $file = $request->file('rep_document');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $destinationPath = public_path('uploads/documents');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);

            $user->rep_document = 'uploads/documents/' . $fileName;
        }

        $user->update([
            'rep_full_name' => $request->rep_full_name,
            'rep_position' => $request->rep_position,
            'rep_fiscal_code' => $request->rep_fiscal_code,
            'profile_completed' => true,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Profile completed successfully');
    }


    /*
    |--------------------------------------------------------------------------
    | FORGOT PASSWORD
    |--------------------------------------------------------------------------
    */

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Reset link sent to email');
        }

        return back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {

                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('success', 'Password has been reset successfully!');
        }

        return back()->withErrors(['email' => [__($status)]]);
    }
}