<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Video extends Model
{
    protected $fillable = [
        'youtube_url',
        'youtube_id',
        'thumbnail_url',
        'title',
        'sort_order',
        'created_by',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Relasi ke Admin pembuat data video.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    /**
     * Scope query untuk mengurutkan video berdasarkan sort_order ASC.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Scope query untuk pencarian berdasarkan judul video.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where('title', 'like', "%{$search}%");
    }

    /**
     * Accessor URL Thumbnail publik (custom upload WebP atau fallback thumbnail YouTube).
     */
    public function getDisplayThumbnailUrlAttribute(): ?string
    {
        if ($this->thumbnail_url) {
            $path = str_replace('\\', '/', $this->thumbnail_url);

            if (Str::startsWith($path, ['http://', 'https://'])) {
                return $path;
            }

            if (Str::startsWith($path, ['storage/', '/storage/'])) {
                return asset(ltrim($path, '/'));
            }

            return asset('storage/'.ltrim($path, '/'));
        }

        if ($this->youtube_id) {
            return "https://img.youtube.com/vi/{$this->youtube_id}/hqdefault.jpg";
        }

        return null;
    }
}
