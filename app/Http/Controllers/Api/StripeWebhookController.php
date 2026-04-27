<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $secret
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid webhook'], 400);
        }

        /*
        |-----------------------------------------
        | PAYMENT SUCCESS (CHECKOUT COMPLETED)
        |-----------------------------------------
        */
        if ($event->type === 'checkout.session.completed') {

            $session = $event->data->object;

            $userId = $session->metadata->user_id ?? null;
            $planId = $session->metadata->plan_id ?? null;

            if (!$userId || !$planId) {
                return response()->json(['error' => 'Missing metadata'], 400);
            }

            $plan = Plan::find($planId);

            if (!$plan) {
                return response()->json(['error' => 'Plan not found'], 404);
            }

            /*
            |-----------------------------------------
            | CREATE OR UPDATE SUBSCRIPTION
            |-----------------------------------------
            */
            $subscription = Subscription::updateOrCreate(
                ['user_id' => $userId],
                [
                    'plan_id' => $plan->id,

                    'status' => 'active',

                    'stripe_customer_id' => $session->customer ?? null,
                    'stripe_subscription_id' => $session->subscription ?? null,

                    'current_period_start' => now(),
                    'current_period_end' => now()->addMonth(),

                    'trial_ends_at' => now()->addDays(14),

                    'canceled_at' => null,
                ]
            );

            /*
            |-----------------------------------------
            | SET USER ROLE / STATUS (OPTIONAL)
            |-----------------------------------------
            */
            $user = User::find($userId);

            if ($user) {
                $user->update([
                    'subscription_status' => 'active'
                ]);
            }
        }

        /*
        |-----------------------------------------
        | SUBSCRIPTION UPDATED
        |-----------------------------------------
        */
        if ($event->type === 'customer.subscription.updated') {

            $data = $event->data->object;

            Subscription::where('stripe_subscription_id', $data->id)
                ->update([
                    'status' => 'active',
                    'current_period_start' => Carbon::createFromTimestamp($data->current_period_start),
                    'current_period_end' => Carbon::createFromTimestamp($data->current_period_end),
                ]);
        }

        /*
        |-----------------------------------------
        | SUBSCRIPTION CANCELED
        |-----------------------------------------
        */
        if ($event->type === 'customer.subscription.deleted') {

            $data = $event->data->object;

            Subscription::where('stripe_subscription_id', $data->id)
                ->update([
                    'status' => 'canceled',
                    'canceled_at' => now()
                ]);
        }

        /*
        |-----------------------------------------
        | PAYMENT FAILED
        |-----------------------------------------
        */
        if ($event->type === 'invoice.payment_failed') {

            $invoice = $event->data->object;

            Subscription::where('stripe_customer_id', $invoice->customer)
                ->update([
                    'status' => 'past_due'
                ]);
        }

        return response()->json(['status' => true]);
    }
}