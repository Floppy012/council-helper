<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    public $casts = [
        'raw' => 'json',
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
}
