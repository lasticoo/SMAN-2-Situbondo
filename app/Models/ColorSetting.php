<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColorSetting extends Model
{
    protected $table = 'color';

    protected $fillable = [
        'primary_color',
        'secondary_color',
        'updated_by',
    ];

    public function updater()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }
}
