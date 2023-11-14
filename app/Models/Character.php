<?php

namespace App\Models;

use App\Enum\CharacterRegion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Character extends Model
{
    protected $casts = [
        'region' => CharacterRegion::class,
    ];

    protected $hidden = [
        'id',
    ];

    public function analyzedReports(): HasMany
    {
        return $this->hasMany(AnalyzedReport::class);
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(
            Team::class,
            'characters_teams',
            'character_id',
            'team_id'
        );
    }
}
