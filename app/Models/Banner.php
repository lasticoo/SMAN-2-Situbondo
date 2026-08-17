<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_url',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Accessor URL Gambar publik yang mengarah ke storage/app/public/banners/
     */
    public function getDisplayImageUrlAttribute(): ?string
    {
        if (! $this->image_url) {
            return null;
        }

        $path = str_replace('\\', '/', $this->image_url);

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (Str::startsWith($path, ['/build/', 'build/'])) {
            return asset(ltrim($path, '/'));
        }

        if (Str::startsWith($path, ['storage/', '/storage/'])) {
            return asset(ltrim($path, '/'));
        }

        return asset('storage/'.ltrim($path, '/'));
    }

    /**
     * Auto-clear caches on update or delete (Invalidate-on-Write)
     */
    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('landing_active_banners');
        });

        static::deleted(function () {
            Cache::forget('landing_active_banners');
        });
    }
}
