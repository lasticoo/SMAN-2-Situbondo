<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'thumbnail_url',
        'summary',
        'content',
        'status',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    /**
     * Auto-clear caches on update or delete (Invalidate-on-Write)
     */
    protected static function booted(): void
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('landing_announcements_top5');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('landing_announcements_top5');
        });
    }
}
