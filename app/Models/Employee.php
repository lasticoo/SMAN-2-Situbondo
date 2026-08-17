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

    /**
     * Auto-clear caches on update or delete (Invalidate-on-Write)
     */
    protected static function booted(): void
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('landing_employee_stats');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('landing_employee_stats');
        });
    }
}
