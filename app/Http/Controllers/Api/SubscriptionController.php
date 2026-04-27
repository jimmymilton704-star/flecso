<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class SubscriptionController extends Controller
{
    /*
    |-----------------------------------------
    | GET CURRENT SUBSCRIPTION
    |-----------------------------------------
    */
    public function index()
    {
        $adminId = auth()->id();

        $sub = Subscription::with('plan')
            ->where('user_id', $adminId)
            ->latest()
            ->first();

        return response()->json([
            'status' => true,
            'data' => $sub
        ]);
    }

    /*
    |-----------------------------------------
    | CREATE STRIPE CHECKOUT SESSION
    |-----------------------------------------
    */
    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $plan = Plan::findOrFail($request->plan_id);
        $adminId = auth()->id();

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',

            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $plan->name,
                    ],
                    'unit_amount' => $plan->price * 100,
                ],
                'quantity' => 1,
            ]],

            'success_url' => env('APP_URL') . '/payment-success',
            'cancel_url'  => env('APP_URL') . '/payment-cancel',

            'metadata' => [
                'user_id' => $adminId,
                'plan_id' => $plan->id,
            ],
        ]);

        return response()->json([
            'status' => true,
            'url' => $session->url
        ]);
    }

    /*
    |-----------------------------------------
    | CANCEL SUBSCRIPTION
    |-----------------------------------------
    */
    public function cancel()
    {
        $adminId = auth()->id();

        $sub = Subscription::where('user_id', $adminId)->first();

        if (!$sub) {
            return response()->json([
                'status' => false,
                'message' => 'No subscription found'
            ], 404);
        }

        $sub->update([
            'status' => 'canceled',
            'canceled_at' => now()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Subscription canceled successfully'
        ]);
    }

    /*
    |-----------------------------------------
    | CHANGE PLAN
    |-----------------------------------------
    */
    public function changePlan(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $adminId = auth()->id();

        $plan = Plan::findOrFail($request->plan_id);

        $sub = Subscription::where('user_id', $adminId)->first();

        if (!$sub) {
            return response()->json([
                'status' => false,
                'message' => 'No subscription found'
            ], 404);
        }

        $oldPlan = $sub->plan;

        $sub->update([
            'plan_id' => $plan->id,
            'status' => 'active'
        ]);

        /*
        |-----------------------------------------
        | DOWNGRADE LOGIC
        |-----------------------------------------
        */
        if ($plan->driver_limit !== null) {

            $drivers = User::where('parent_id', $adminId)
                ->where('role', 'driver')
                ->orderBy('id')
                ->get();

            foreach ($drivers as $index => $driver) {
                if ($index >= $plan->driver_limit) {
                    $driver->status = 'inactive';
                    $driver->save();
                }
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Plan updated successfully',
            'data' => [
                'old_plan' => $oldPlan->name ?? null,
                'new_plan' => $plan->name
            ]
        ]);
    }

    /*
    |-----------------------------------------
    | ACTIVE SUBSCRIPTION
    |-----------------------------------------
    */
    public function getActiveSubscription()
    {
        $adminId = auth()->id();

        $subscription = Subscription::with('plan')
            ->where('user_id', $adminId)
            ->latest()
            ->first();

        if (!$subscription) {
            return response()->json([
                'status' => false,
                'message' => 'No subscription found'
            ]);
        }

        $now = now();

        if ($subscription->trial_ends_at && $now->lt($subscription->trial_ends_at)) {
            return response()->json([
                'status' => true,
                'type' => 'trial',
                'data' => [
                    'plan_id'   => null,
                    'plan_name' => 'trial',
                    'expires_at'=> $subscription->trial_ends_at,
                    'days_left' => $now->diffInDays($subscription->trial_ends_at),
                ]
            ]);
        }

        $plan = $subscription->plan;

        if (!$plan) {
            return response()->json([
                'status' => false,
                'message' => 'No active plan found'
            ]);
        }

        return response()->json([
            'status' => true,
            'type' => strtolower($plan->name),

            'data' => [
                'plan_id'   => $plan->id,
                'plan_name' => $plan->name,
                'driver_limit' => $plan->driver_limit ?? 'unlimited',
                'expires_at'=> $subscription->current_period_end,
            ]
        ]);
    }
}