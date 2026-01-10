<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    protected $fillable = [
        'name',
        'type',
        'status',
        'ip_address',
        'last_seen',
    ];

    protected $casts = [
        'last_seen' => 'datetime',
    ];
}
