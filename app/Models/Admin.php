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
        'role',
        'avatar_url',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get full URL or fallback placeholder for admin avatar.
     */
    public function getDisplayAvatarUrlAttribute(): string
    {
        if ($this->avatar_url) {
            return asset('storage/'.$this->avatar_url);
        }

        return 'https://placehold.co/150x150/1e293b/ffffff?text='.urlencode(substr($this->name ?? 'A', 0, 2));
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
