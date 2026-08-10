<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpmbDocument extends Model
{
    protected $fillable = [
        'spmb_info_id',
        'title',
        'file_url',
    ];

    public function spmbInfo()
    {
        return $this->belongsTo(SpmbInfo::class, 'spmb_info_id');
    }
}
