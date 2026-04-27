<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    use HasFactory;

    protected $fillable = [

        'admin_id',

        // Basic Info
        'container_id',
        'container_license_number',
        'container_type',
        'status',
        'weight_capacity',
        'image',

        // ISO Identification
        'owner_code',
        'category_identifier',
        'serial_number',
        'check_digit',
        'iso_type_size_code',

        // Technical & Safety
        'manufacturer_serial_number',
        'manufacture_date',
        'max_operating_weight',
        'stacking_weight',
        'next_examination_date',

        // Custom & Logistics
        'eori_number',
        'seal_number',
        'container_status',
        'owner_lessor',
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

    /*
    |-----------------------------------------
    | ACCESSORS
    |-----------------------------------------
    */

    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset($this->image)
            : null;
    }
}