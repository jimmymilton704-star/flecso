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
        'truck_verified',
        'container_verified',

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
        'total_fuel_cost',
        'total_fuel_liters',
        'fuel_cost_per_km',
        'avg_kmpl',

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
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class, 'truck_id');
    }

    public function container()
    {
        return $this->belongsTo(Container::class, 'container_id');
    }
    public function fuelLogs()
    {
        return $this->hasMany(FuelLog::class);
    }
    public function account()
    {
        return $this->hasOne(TripAccount::class);
    }

    public function accountTransactions()
    {
        return $this->hasMany(TripAccountTransaction::class);
    }
}
