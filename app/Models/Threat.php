<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Threat extends Model
{
    protected $fillable = [
        'type',
        'severity',
        'source_ip',
        'target_system',
        'status',
        'description',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];
}
