<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolProfile extends Model
{
    protected $table = 'school_profile';

    protected $fillable = [
        'vision',
        'mission',
        'goals',
        'history',
        'structure_image_url',
        'about_us',
    ];
}
