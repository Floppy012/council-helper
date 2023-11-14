<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public function catalystSourceItems(): BelongsToMany
    {
        return $this->belongsToMany(
            Item::class,
            'catalyst_items_2_items',
            'catalyst_item_id',
            'item_id',
        );
    }

    public function catalystItems(): BelongsToMany
    {
        return $this->belongsToMany(
            Item::class,
            'catalyst_items_2_items',
            'item_id',
            'catalyst_item_id'
        );
    }

    public function simResults(): HasMany
    {
        return $this->hasMany(ItemSimResult::class);
    }
}
