<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TruckHealthLog extends Model
{
    protected $fillable = [
        'truck_id',
        'current_km',
        'engine_hours',
        'recorded_at'
    ];

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }
}