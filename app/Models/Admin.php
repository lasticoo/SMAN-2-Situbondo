<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function news()
    {
        return $this->hasMany(News::class, 'created_by');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'created_by');
    }

    public function videos()
    {
        return $this->hasMany(Video::class, 'created_by');
    }

    public function importBatches()
    {
        return $this->hasMany(ImportBatch::class, 'uploaded_by');
    }
}
