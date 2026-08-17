<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportBatch extends Model
{
    public const STATUS_PROCESSING = 'processing';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'uploaded_by',
        'file_name',
        'total_rows',
        'success_rows',
        'failed_rows',
        'status',
        'error_log',
    ];

    public function uploader()
    {
        return $this->belongsTo(Admin::class, 'uploaded_by');
    }

    public function graduationRecords()
    {
        return $this->hasMany(GraduationRecord::class, 'import_batch_id');
    }
}
