<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /*
    |-----------------------------------------
    | GET ALL PLANS
    |-----------------------------------------
    */
    public function index()
    {
        $plans = Plan::get()->map(function ($plan) {
            return [
                'id'            => $plan->id,
                'name'          => $plan->name,
                'price'         => $plan->price,
                'driver_limit'  => $plan->driver_limit ?? 'unlimited',

                // Optional features (for UI)
                'features' => $plan->name === 'basic'
                    ? [
                        'Max 10 Drivers',
                        'Basic Support'
                    ]
                    : [
                        'Unlimited Drivers',
                        'Priority Support',
                        'Advanced Analytics'
                    ]
            ];
        });

        return response()->json([
            'status'  => true,
            'message' => 'Plans fetched successfully',
            'data'    => $plans
        ]);
    }
}