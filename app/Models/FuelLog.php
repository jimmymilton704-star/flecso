<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'trip_id',
        'truck_id',
        'driver_id',
        'fuel_liters',
        'fuel_price_per_liter',
        'total_cost',
        'fuel_station',
        'location',
        'latitude',
        'longitude',
        'odometer_reading',
        'notes',
        'refuel_time'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
