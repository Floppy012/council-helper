<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemSimResult extends Model
{
    protected $casts = [
        'mean' => 'double',
        'min' => 'double',
        'max' => 'double',
        'median' => 'double',
        'mean_gain' => 'double',
        'min_gain' => 'double',
        'max_gain' => 'double',
        'median_gain' => 'double',
    ];

    protected $hidden = [
        'id',
    ];

    public function analyzedReport(): BelongsTo
    {
        return $this->belongsTo(AnalyzedReport::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }
}
