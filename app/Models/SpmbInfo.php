<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpmbInfo extends Model
{
    protected $table = 'spmb_info';

    protected $fillable = [
        'banner_url',
        'schedule_info',
        'requirements_info',
        'period_start',
        'period_end',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    public function documents()
    {
        return $this->hasMany(SpmbDocument::class, 'spmb_info_id');
    }
}
