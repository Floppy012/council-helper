<?php

namespace App\Models;

use App\Enum\IngestJobStatus;
use App\Enum\IngestJobType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IngestJob extends Model
{
    protected $casts = [
        'type' => IngestJobType::class,
        'status' => IngestJobStatus::class,
        'errors' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = [
        'id',
        'report_id',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}
