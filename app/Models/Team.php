<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    protected $casts = [
        'wowaudit_secret' => 'encrypted',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = [
        'id',
        'wowaudit_secret',
    ];

    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(
            Character::class,
            'characters_teams',
            'team_id',
            'character_id'
        );
    }
}
