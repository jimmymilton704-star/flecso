<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',

        'stripe_customer_id',
        'stripe_subscription_id',
        'stripe_price_id',

        'status',

        'trial_ends_at',

        'current_period_start',
        'current_period_end',

        'canceled_at',

        'extra_drivers',
        'extra_cost',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'canceled_at' => 'datetime',
    ];

    /*
    |-----------------------------------------
    | RELATIONS
    |-----------------------------------------
    */

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |-----------------------------------------
    | HELPERS
    |-----------------------------------------
    */

    public function isTrialActive(): bool
    {
        return $this->status === 'trial'
            && $this->trial_ends_at
            && Carbon::now()->lt($this->trial_ends_at);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCanceled(): bool
    {
        return $this->status === 'canceled';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    public function getDriverLimit()
    {
        if (!$this->plan) {
            return 0;
        }

        return $this->plan->driver_limit; // null = unlimited
    }
}