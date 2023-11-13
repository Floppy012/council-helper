<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    public $timestamps = false;

    protected $casts = [
        'blizzard_item_id' => 'int',
        'catalyst' => 'boolean',
    ];

    protected $hidden = [
        'id',
        'encounter_id',
    ];

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function catalystSourceItems(): HasMany
    {
        return $this->hasMany(Item::class, 'catalyst_item_id');
    }

    public function catalystItem(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'catalyst_item_id');
    }

    public function simResults(): HasMany
    {
        return $this->hasMany(ItemSimResult::class);
    }
}
