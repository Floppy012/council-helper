<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportRawData extends Model
{
    public $timestamps = false;

    protected $casts = [
        'data' => 'object',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}
