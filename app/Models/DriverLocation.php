<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'latitude',
        'longitude',
        'speed',
        'heading',
    ];

    public $timestamps = false;

    /*
    |-----------------------------------------
    | RELATION
    |-----------------------------------------
    */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}