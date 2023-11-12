<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Encounter extends Model
{
    public $timestamps = false;

    protected $hidden = [
        'id',
        'raid_id',
    ];

    public function raid(): BelongsTo
    {
        return $this->belongsTo(Raid::class);
    }
}
