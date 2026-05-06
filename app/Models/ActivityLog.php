<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'action',
        'method',
        'route',
        'model',
        'model_id',
        'payload',
        'response',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'payload' => 'array',
        'response' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
