<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Report extends Model
{
    public $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $hidden = [
        'id',
    ];

    public function ingestJobs(): HasMany
    {
        return $this->hasMany(IngestJob::class);
    }

    public function rawData(): HasOne
    {
        return $this->hasOne(ReportRawData::class);
    }

    public function analyzedReport(): HasOne
    {
        return $this->hasOne(AnalyzedReport::class);
    }
}
