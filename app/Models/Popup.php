<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_url',
        'start_date',
        'end_date',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Accessor URL Gambar publik yang mengarah ke storage/app/public/popups/
     */
    public function getDisplayImageUrlAttribute(): ?string
    {
        if (! $this->image_url) {
            return null;
        }

        $path = str_replace('\\', '/', $this->image_url);

        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (\Illuminate\Support\Str::startsWith($path, ['/build/', 'build/'])) {
            return asset(ltrim($path, '/'));
        }

        if (\Illuminate\Support\Str::startsWith($path, ['storage/', '/storage/'])) {
            return asset(ltrim($path, '/'));
        }

        return asset('storage/' . ltrim($path, '/'));
    }
}
