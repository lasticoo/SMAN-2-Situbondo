<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'photo_url',
        'name',
        'nip',
        'position',
        'extra_info',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope query untuk filter pegawai yang aktif.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope query untuk pencarian berdasarkan nama, NIP, posisi, atau informasi tambahan.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('nip', 'like', "%{$search}%")
                ->orWhere('position', 'like', "%{$search}%")
                ->orWhere('extra_info', 'like', "%{$search}%");
        });
    }

    /**
     * Scope query untuk filter posisi/jabatan pegawai.
     */
    public function scopePositionFilter(Builder $query, ?string $filter): Builder
    {
        if (empty($filter) || $filter === 'all') {
            return $query;
        }

        if ($filter === 'Guru') {
            return $query->where(function (Builder $q) {
                $q->where('position', 'like', '%Guru%')
                    ->orWhere('position', 'like', '%Pengajar%');
            });
        }

        if ($filter === 'Kepala Sekolah') {
            return $query->where('position', 'like', '%Kepala Sekolah%');
        }

        if ($filter === 'Staf Administrasi') {
            return $query->where(function (Builder $q) {
                $q->where('position', 'like', '%Staf%')
                    ->orWhere('position', 'like', '%Tata Usaha%')
                    ->orWhere('position', 'like', '%Admin%');
            });
        }

        return $query;
    }
}
