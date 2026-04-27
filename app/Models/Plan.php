<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'driver_limit',
        'stripe_price_id',
        'stripe_product_id',
        'is_active',
        'description'
    ];

    /*
    |--------------------------------------
    | HELPERS
    |--------------------------------------
    */

    public function isUnlimited(): bool
    {
        return is_null($this->driver_limit);
    }

    public function getDriverLimitLabelAttribute()
    {
        return $this->driver_limit ?? 'unlimited';
    }
}