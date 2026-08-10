<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'module',
        'reference_id',
        'file_url',
        'original_size_kb',
        'optimized_size_kb',
    ];
}
