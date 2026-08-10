<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GraduationRecord extends Model
{
    protected $fillable = [
        'template_id',
        'import_batch_id',
        'is_announced',
        'announced_at',
        'nisn',
        'student_name',
        'graduation_status',
        'document_url',
        'is_downloadable',
    ];

    protected $casts = [
        'is_announced' => 'boolean',
        'announced_at' => 'datetime',
        'is_downloadable' => 'boolean',
    ];

    public function template()
    {
        return $this->belongsTo(SklTemplate::class, 'template_id');
    }

    public function importBatch()
    {
        return $this->belongsTo(ImportBatch::class, 'import_batch_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'nisn', 'nisn');
    }
}
