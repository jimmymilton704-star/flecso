<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'trip_id',
        'driver_id',
        'truck_id',
        'opening_amount',
        'total_expense',
        'remaining_amount',
        'status',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }

    public function transactions()
    {
        return $this->hasMany(TripAccountTransaction::class);
    }
}