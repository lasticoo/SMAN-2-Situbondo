<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class ColorSetting extends Model
{
    protected $table = 'color';

    protected $fillable = [
        'primary_color',
        'secondary_color',
        'updated_by',
    ];

    /**
     * Relasi ke Admin yang terakhir memperbarui konfigurasi warna.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    /**
     * Mengambil konfigurasi warna aktif (singleton).
     * Jika belum ada data tersimpan di database, menggunakan fallback dari config('theme').
     */
    public static function current(): self
    {
        return Cache::remember('active_theme_color_setting', 86400, function () {
            $setting = static::first();

            if (! $setting) {
                $setting = new static([
                    'primary_color' => config('theme.primary', '#001C4D'),
                    'secondary_color' => config('theme.secondary', '#5C5F60'),
                ]);
            }

            return $setting;
        });
    }

    /**
     * Auto-clear caches on update or delete (Invalidate-on-Write)
     */
    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('active_theme_color_setting');
            Cache::forget('user_profile_color_setting');
            Cache::forget('landing_theme_colors');
            Cache::forget('landing_color_setting');
        });

        static::deleted(function () {
            Cache::forget('active_theme_color_setting');
            Cache::forget('user_profile_color_setting');
            Cache::forget('landing_theme_colors');
            Cache::forget('landing_color_setting');
        });
    }
}
