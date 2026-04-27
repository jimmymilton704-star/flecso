<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TruckMaintenance extends Model
{
    protected $table = 'truck_maintenance';

    protected $fillable = [
        'truck_id',
        'type',
        'last_service_km',
        'next_due_km',
        'scheduled_date',
        'completed_date',
        'status'
    ];

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }
}
