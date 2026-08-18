<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Gallery extends Model
{
    protected $fillable = [
        'photo_url',
        'activity_name',
        'activity_date',
        'sort_order',
        'created_by',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'sort_order' => 'integer',
    ];

    /**
     * Relasi ke Admin pembuat data galeri.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    /**
     * Scope query untuk mengurutkan galeri berdasarkan sort_order ASC.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Scope query untuk pencarian berdasarkan nama kegiatan.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where('activity_name', 'like', "%{$search}%");
    }

    /**
     * Accessor URL Gambar publik yang mengarah ke storage public.
     */
    public function getDisplayPhotoUrlAttribute(): ?string
    {
        if (! $this->photo_url) {
            return null;
        }

        $path = str_replace('\\', '/', $this->photo_url);

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (Str::startsWith($path, ['storage/', '/storage/'])) {
            return asset(ltrim($path, '/'));
        }

        return asset('storage/'.ltrim($path, '/'));
    }
}
