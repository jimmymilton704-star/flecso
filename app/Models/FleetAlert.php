<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FleetAlert extends Model
{
    protected $fillable = [
        'admin_id',
        'truck_id',
        'type',
        'priority',
        'message',
        'is_read'
    ];

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
