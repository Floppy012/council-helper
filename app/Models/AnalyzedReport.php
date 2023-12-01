<?php

namespace App\Models;

use App\Enum\CharacterClass;
use App\Enum\CharacterRace;
use App\Enum\CharacterSpec;
use App\Enum\RaidDifficulty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnalyzedReport extends Model
{
    protected $casts = [
        'raid_difficulty' => RaidDifficulty::class,
        'class_id' => CharacterClass::class,
        'race_id' => CharacterRace::class,
        'spec_id' => CharacterSpec::class,
        'dps_mean' => 'double',
        'dps_median' => 'double',
        'dps_min' => 'double',
        'dps_max' => 'double',
        'simulated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    public function raid(): BelongsTo
    {
        return $this->belongsTo(Raid::class);
    }

    public function itemSimResults(): HasMany
    {
        return $this->hasMany(ItemSimResult::class);
    }

    public function supersedingReport(): BelongsTo
    {
        return $this->belongsTo(AnalyzedReport::class, 'superseding_id');
    }

    public function supersededReports(): HasMany
    {
        return $this->hasMany(AnalyzedReport::class, 'superseding_id');
    }
}
