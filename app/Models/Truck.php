<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    use HasFactory;

    protected $fillable = [

        // =========================
        // BASIC INFO
        // =========================
        'admin_id',
        'truck_number',
        'driver_id',
        'truck_license_number',
        'capacity_tons',
        'truck_type_category',
        'type',
        'status',
        'image',

        // =========================
        // 1. CORE IDENTITY & LEGAL DOCUMENTS
        // =========================
        'license_plate_number',
        'vin_number',
        'first_registration_date',
        'usage_type',
        'documento_unico',

        // =========================
        // 2. TECHNICAL SPECIFICATIONS
        // =========================
        'vehicle_category',
        'gross_vehicle_weight',
        'gvw_kg',
        'payload_capacity_kg',
        'payload_capacity',
        'number_of_axles',
        'engine_class',
        'fuel_type',

        // =========================
        // 3. COMPLIANCE & SAFETY DEADLINES
        // =========================
        'next_inspection_date',
        'insurance_policy_number',
        'insurance_expiry_date',
        'tachograph_calibration_expiry',
        'bollo_expiry_date',
        'expected_kmpl',
    ];

    protected $casts = [
        'first_registration_date' => 'date',
        'next_inspection_date'    => 'date',
        'insurance_expiry'        => 'date',
        'tachograph_expiry'       => 'date',
        'bollo_expiry'            => 'date',
    ];

    /*
    |----------------------------------------------------
    | RELATION: ADMIN
    |----------------------------------------------------
    */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }


}