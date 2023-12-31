<?php

namespace App\Enum;

enum CharacterSpec: int
{
    case BLOOD_DEATH_KNIGHT = 250;
    case FROST_DEATH_KNIGHT = 251;
    case UNHOLY_DEATH_KNIGHT = 252;

    case HAVOC_DEMON_HUNTER = 577;
    case VENGEANCE_DEMON_HUNTER = 581;

    case BALANCE_DRUID = 102;
    case FERAL_DRUID = 103;
    case GUARDIAN_DRUID = 104;
    case RESTORATION_DRUID = 105;

    case DEVASTATION_EVOKER = 1467;
    case PRESERVATION_EVOKER = 1468;
    case AUGMENTATION_EVOKER = 1473;

    case BEAST_MASTERY_HUNTER = 253;
    case MARKSMANSHIP_HUNTER = 254;
    case SURVIVAL_HUNTER = 255;

    case ARCANE_MAGE = 62;
    case FIRE_MAGE = 63;
    case FROST_MAGE = 64;

    case BREWMASTER_MONK = 268;
    case MISTWEAVER_MONK = 270;
    case WINDWALKER_MONK = 269;

    case HOLY_PALADIN = 65;
    case PROTECTION_PALADIN = 66;
    case RETRIBUTION_PALADIN = 70;

    case DISCIPLINE_PRIEST = 256;
    case HOLY_PRIEST = 257;
    case SHADOW_PRIEST = 258;

    case ASSASSINATION_ROGUE = 259;
    case OUTLAW_ROGUE = 260;
    case SUBTLETY_ROGUE = 261;

    case ELEMENTAL_SHAMAN = 262;
    case ENHANCEMENT_SHAMAN = 263;
    case RESTORATION_SHAMAN = 264;

    case AFFLICTION_WARLOCK = 265;
    case DEMONOLOGY_WARLOCK = 266;
    case DESTRUCTION_WARLOCK = 267;

    case ARMS_WARRIOR = 71;
    case FURY_WARRIOR = 72;
    case PROTECTION_WARRIOR = 73;

    public function name(): string
    {
        return ucwords(str_replace('_', ' ', strtolower($this->name)));
    }
}
