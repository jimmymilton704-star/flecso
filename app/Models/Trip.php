<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [

        'admin_id',

        // Basic
        'trip_id',
        'trip_type',
        'driver_id',
        'truck_id',
        'container_id',
        'pickup_location',
        'delivery_location',
        'pickup_lat',
        'pickup_lng',
        'delivery_lat',
        'delivery_lng',
        'distance_km',
        'eta_mins',
        'schedule_datetime',
        'payment_amount',
        'trip_status',

        // Delivery Contact
        'delivery_name',
        'delivery_phone',
        'delivery_email',

        // Package
        'package_description',
        'package_weight',
        'package_height',
        'package_length',
        'package_width',
    ];

    /*
    |-----------------------------------------
    | RELATIONS
    |-----------------------------------------
    */

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class, 'truck_id');
    }

    public function container()
    {
        return $this->belongsTo(Container::class, 'container_id');
    }
}