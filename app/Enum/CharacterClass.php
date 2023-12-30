<?php

namespace App\Enum;

enum CharacterClass: int
{
    case WARRIOR = 1;
    case PALADIN = 2;
    case HUNTER = 3;
    case ROGUE = 4;
    case PRIEST = 5;
    case DEATH_KNIGHT = 6;
    case SHAMAN = 7;
    case MAGE = 8;
    case WARLOCK = 9;
    case MONK = 10;
    case DRUID = 11;
    case DEMON_HUNTER = 12;
    case EVOKER = 13;

    public function hexColor(): string
    {
        return match ($this) {
            CharacterClass::WARRIOR => '#C69B6D',
            CharacterClass::PALADIN => '#F48CBA',
            CharacterClass::HUNTER => '#AAD372',
            CharacterClass::ROGUE => '#FFF468',
            CharacterClass::PRIEST => '#FFFFFF',
            CharacterClass::DEATH_KNIGHT => '#C41E3A',
            CharacterClass::SHAMAN => '#0070DD',
            CharacterClass::MAGE => '#3FC7EB',
            CharacterClass::WARLOCK => '#8788EE',
            CharacterClass::MONK => '#00FF98',
            CharacterClass::DRUID => '#FF7C0A',
            CharacterClass::DEMON_HUNTER => '#A330C9',
            CharacterClass::EVOKER => '#33937F',
        };
    }

    public function name(): string
    {
        return ucwords(str_replace('_', ' ', strtolower($this->name)));
    }
}
