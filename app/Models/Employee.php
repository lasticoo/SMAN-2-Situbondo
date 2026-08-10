<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'photo_url',
        'name',
        'nip',
        'position',
        'extra_info',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
