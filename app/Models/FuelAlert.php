<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelAlert extends Model
{
    use HasFactory;
    protected $fillable = [
        'trip_id',
        'truck_id',
        'driver_id',
        'alert_type',
        'message',
        'is_resolved'
    ];
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
