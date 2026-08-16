<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    /**
     * Scope query untuk hanya mengambil siswa dengan status is_public = true.
     */
    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope query pencarian berdasarkan NISN, Nama, atau Kelas.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('nisn', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('class', 'like', "%{$search}%")
                ->orWhere('extra_info', 'like', "%{$search}%");
        });
    }

    /**
     * Scope query filter berdasarkan kelas.
     */
    public function scopeClassFilter(Builder $query, ?string $classFilter): Builder
    {
        if (empty($classFilter) || $classFilter === 'all') {
            return $query;
        }

        if (in_array($classFilter, ['10', '11', '12'])) {
            $roman = match ($classFilter) {
                '10' => 'X',
                '11' => 'XI',
                '12' => 'XII',
            };

            return $query->where(function (Builder $q) use ($classFilter, $roman) {
                $q->where('class', 'like', "{$roman}-%")
                    ->orWhere('class', 'like', "{$roman} %")
                    ->orWhere('class', $roman)
                    ->orWhere('class', 'like', "{$classFilter}%");
            });
        }

        return $query->where('class', $classFilter);
    }

    public function graduationRecords()
    {
        return $this->hasMany(GraduationRecord::class, 'nisn', 'nisn');
    }
}
