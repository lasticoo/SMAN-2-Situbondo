<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'nisn',
        'name',
        'class',
        'extra_info',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function graduationRecords()
    {
        return $this->hasMany(GraduationRecord::class, 'nisn', 'nisn');
    }

    /**
     * Auto-clear caches on update or delete (Invalidate-on-Write)
     */
    protected static function booted(): void
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('landing_student_stats');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('landing_student_stats');
        });
    }
}
