<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
   use HasFactory, HasApiTokens;

    protected $fillable = [

        'admin_id',

        // Photo
        'driver_photo',

        // Personal
        'full_name',
        'email',
        'phone',
        'password',

        // License
        'license_number',
        'license_expiry',
        'status',

        // Legal
        'place_of_birth',
        'date_of_birth',
        'fiscal_code',
        'residential_address',
        'nationality',
        'work_permit_number',
        'work_permit_expiry',
        'medical_fitness_date',
        'criminal_record_check',

        // Professional
        'driving_license_category',
        'cqc_number',
        'cqc_expiry',
        'tachograph_card_number',

        // Documents
        'license_front',
        'license_back',
        'cqc_card',
        'work_permit_file',
        'medical_certificate',
    ];

    protected $hidden = [
        'password',
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
    | ACCESSORS (IMAGE URLS)
    |-----------------------------------------
    */
    public function getDriverPhotoUrlAttribute()
    {
        return $this->driver_photo ? asset($this->driver_photo) : null;
    }

    public function location()
    {
        return $this->hasOne(\App\Models\DriverLocation::class);
    }

    public function truck() {
        return $this->hasOne(Truck::class);
    }

}