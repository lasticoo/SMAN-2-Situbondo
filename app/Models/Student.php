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
}
