<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripAccountTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_account_id',
        'trip_id',
        'driver_id',
        'type',
        'amount',
        'expense_date',
        'title',
        'description',
        'balance_before',
        'balance_after',
        'source_type',
        'source_name',
        'source_id',
    ];

    protected $casts = [
        'expense_date' => 'date',
    ];

    public function account()
    {
        return $this->belongsTo(TripAccount::class, 'trip_account_id');
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}