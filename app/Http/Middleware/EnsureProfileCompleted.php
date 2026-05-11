<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureProfileCompleted
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | NOT LOGGED IN
        |--------------------------------------------------------------------------
        */
        if (!$user) {
            return redirect()->route('login');
        }

        /*
        |--------------------------------------------------------------------------
        | IF PROFILE ALREADY COMPLETED
        |--------------------------------------------------------------------------
        */
        if ($user->profile_completed) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | ALLOWED ROUTES BEFORE PROFILE COMPLETION
        |--------------------------------------------------------------------------
        */
        $allowedRoutes = [
            'profile.step1',
            'profile.step2',
            'profile.step3',
            'profile.step4',

            'profile.step1.post',
            'profile.step2.post',
            'profile.step3.post',
            'profile.step4.post',

            'logout',
        ];

        /*
        |--------------------------------------------------------------------------
        | CURRENT ROUTE
        |--------------------------------------------------------------------------
        */
        $currentRoute = optional($request->route())->getName();

        /*
        |--------------------------------------------------------------------------
        | BLOCK ALL OTHER ROUTES
        |--------------------------------------------------------------------------
        */
        if (!in_array($currentRoute, $allowedRoutes)) {

            /*
            |--------------------------------------------------------------------------
            | REDIRECT USER TO CORRECT STEP
            |--------------------------------------------------------------------------
            */

            // STEP 1
            if (
                empty($user->company_legal_name) ||
                empty($user->company_type) ||
                empty($user->vat_number) ||
                empty($user->fiscal_code) ||
                empty($user->rea_number)
            ) {

                return redirect()
                    ->route('profile.step1')
                    ->with(
                        'error',
                        'Please complete Step 1 first.'
                    );
            }

            // STEP 2
            if (
                empty($user->pec_email) ||
                empty($user->sdi_code) ||
                empty($user->registered_address) ||
                empty($user->city) ||
                empty($user->province) ||
                empty($user->zip_code)
            ) {

                return redirect()
                    ->route('profile.step2')
                    ->with(
                        'error',
                        'Please complete Step 2 first.'
                    );
            }

            // STEP 3
            if (
                empty($user->ren_number) ||
                is_null($user->fleet_trucks) ||
                is_null($user->fleet_vans) ||
                is_null($user->fleet_containers) ||
                empty($user->insurance_policy_number)
            ) {

                return redirect()
                    ->route('profile.step3')
                    ->with(
                        'error',
                        'Please complete Step 3 first.'
                    );
            }

            // STEP 4
            if (
                empty($user->rep_full_name) ||
                empty($user->rep_position) ||
                empty($user->rep_fiscal_code) ||
                empty($user->rep_document)
            ) {

                return redirect()
                    ->route('profile.step4')
                    ->with(
                        'error',
                        'Please complete Step 4 first.'
                    );
            }
        }

        return $next($request);
    }
}