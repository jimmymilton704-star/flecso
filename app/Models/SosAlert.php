<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SosAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'admin_id',
        'trip_id',
        'emergency_type',
        'description',
        'photo',
        'location',
        'status'
    ];

    /*
    |-----------------------------------------
    | RELATIONS
    |-----------------------------------------
    */

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    /*
    |-----------------------------------------
    | ACCESSOR (PHOTO URL)
    |-----------------------------------------
    */
    public function getPhotoUrlAttribute()
    {
        return $this->photo ? asset($this->photo) : null;
    }
}