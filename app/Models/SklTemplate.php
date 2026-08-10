<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SklTemplate extends Model
{
    protected $table = 'skl_templates';

    protected $fillable = [
        'name',
        'content',
        'nomor_surat_format',
        'kop_surat_url',
        'ttd_name',
        'ttd_position',
        'ttd_signature_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function graduationRecords()
    {
        return $this->hasMany(GraduationRecord::class, 'template_id');
    }
}
