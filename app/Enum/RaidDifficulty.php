<?php

namespace App\Enum;

enum RaidDifficulty: string
{
    case LFR = 'lfr';
    case NORMAL = 'normal';
    case HEROIC = 'heroic';
    case MYTHIC = 'mythic';

}
