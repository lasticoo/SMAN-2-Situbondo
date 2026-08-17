<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolProfile extends Model
{
    protected $table = 'school_profile';

    protected $fillable = [
        'vision',
        'mission',
        'goals',
        'history',
        'structure_image_url',
        'about_us',
    ];

    /**
     * Auto-clear caches on update or delete
     */
    protected static function booted(): void
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('user_profile_school_data');
            \Illuminate\Support\Facades\Cache::forget('landing_school_profile');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('user_profile_school_data');
            \Illuminate\Support\Facades\Cache::forget('landing_school_profile');
        });
    }
}
