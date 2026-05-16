<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Signup fields
        'name',
        'email',
        'phone',
        'password',
        'role',
        'parent_id',
        'avatar',

        // Step 1 - Company Profile
        'company_legal_name',
        'company_type',
        'vat_number',
        'fiscal_code',
        'rea_number',
        'company_name',

        // Step 2 - Billing
        'pec_email',
        'sdi_code',
        'registered_address',
        'city',
        'province',
        'zip_code',

        // Step 3 - Logistics
        'ren_number',
        'eu_license_number',
        'fleet_trucks',
        'fleet_vans',
        'fleet_containers',
        'insurance_policy_number',

        // Step 4 - Legal Rep
        'rep_full_name',
        'rep_position',
        'rep_fiscal_code',
        'rep_document',

        // Status
        'profile_completed',
        'last_seen',

        'email_otp',
        'email_otp_expires_at',
        'email_verified_at',

        'phone_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'document',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'profile_completed' => 'boolean', // ✅ ADD THIS
    ];

    protected $appends = ['avatar_url'];

    public function driverProfile()
    {
        return $this->hasOne(Driver::class, 'driver_id', 'id');
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar
            ? asset($this->avatar) // ✅ FIXED
            : null;
    }
}
