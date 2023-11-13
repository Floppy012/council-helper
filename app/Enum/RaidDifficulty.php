<?php

namespace App\Enum;

enum RaidDifficulty: string
{
    case LFR = 'lfr';
    case NORMAL = 'normal';
    case HEROIC = 'heroic';
    case MYTHIC = 'mythic';

    public function name(): string
    {
        return match ($this) {
            RaidDifficulty::LFR => 'LFR',
            RaidDifficulty::NORMAL => 'Normal',
            RaidDifficulty::HEROIC => 'Heroic',
            RaidDifficulty::MYTHIC => 'Mythic',
        };
    }
}
