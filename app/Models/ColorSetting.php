<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColorSetting extends Model
{
    protected $table = 'color';

    protected $fillable = [
        'primary_color',
        'secondary_color',
        'updated_by',
    ];

    public function updater()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    /**
     * Auto-clear caches on update or delete
     */
    protected static function booted(): void
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('user_profile_color_setting');
            \Illuminate\Support\Facades\Cache::forget('landing_theme_colors');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('user_profile_color_setting');
            \Illuminate\Support\Facades\Cache::forget('landing_theme_colors');
        });
    }
}
