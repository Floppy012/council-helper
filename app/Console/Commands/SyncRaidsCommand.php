<?php

namespace App\Console\Commands;

use App\Models\Encounter;
use App\Models\Raid;
use Illuminate\Console\Command;

class SyncRaidsCommand extends Command
{
    protected $signature = 'sync:raids';

    protected $description = 'Synchronizes raids in the database (without deletion)';

    public function handle(): void
    {
        Raid::upsert([
            [
                'slug' => 'amirdrassil',
                'name' => 'Amirdrassil',
                'blizzard_instance_id' => 1207,
            ],
        ], ['slug']);

        $this->syncAmirdrassilBosses();
    }

    private function syncAmirdrassilBosses(): void
    {
        $amirdrassil = Raid::where('slug', 'amirdrassil')->firstOrFail();

        Encounter::upsert([
            [
                'raid_id' => $amirdrassil->id,
                'slug' => 'trash',
                'name' => 'Trash',
                'order' => 0,
                'blizzard_dungeon_encounter_id' => null,
                'blizzard_journal_encounter_id' => null,
            ],
            [
                'raid_id' => $amirdrassil->id,
                'slug' => 'gnarlroot',
                'name' => 'Gnarlroot',
                'order' => 1,
                'blizzard_dungeon_encounter_id' => 2820,
                'blizzard_journal_encounter_id' => 2564,
            ],
            [
                'raid_id' => $amirdrassil->id,
                'slug' => 'igira',
                'name' => 'Igira',
                'order' => 2,
                'blizzard_dungeon_encounter_id' => 2709,
                'blizzard_journal_encounter_id' => 2554,
            ],
            [
                'raid_id' => $amirdrassil->id,
                'slug' => 'volcoross',
                'name' => 'Volcoross',
                'order' => 3,
                'blizzard_dungeon_encounter_id' => 2737,
                'blizzard_journal_encounter_id' => 2557,
            ],
            [
                'raid_id' => $amirdrassil->id,
                'slug' => 'council-of-dreams',
                'name' => 'Council of Dreams',
                'order' => 4,
                'blizzard_dungeon_encounter_id' => 2728,
                'blizzard_journal_encounter_id' => 2555,
            ],
            [
                'raid_id' => $amirdrassil->id,
                'slug' => 'larodar',
                'name' => 'Larodar',
                'order' => 5,
                'blizzard_dungeon_encounter_id' => 2731,
                'blizzard_journal_encounter_id' => 2553,
            ],
            [
                'raid_id' => $amirdrassil->id,
                'slug' => 'nymue',
                'name' => 'Nymue',
                'order' => 6,
                'blizzard_dungeon_encounter_id' => 2708,
                'blizzard_journal_encounter_id' => 2556,
            ],
            [
                'raid_id' => $amirdrassil->id,
                'slug' => 'smolderon',
                'name' => 'Smolderon',
                'order' => 7,
                'blizzard_dungeon_encounter_id' => 2824,
                'blizzard_journal_encounter_id' => 2563,
            ],
            [
                'raid_id' => $amirdrassil->id,
                'slug' => 'tindral-sageswift',
                'name' => 'Tindral Sageswift',
                'order' => 8,
                'blizzard_dungeon_encounter_id' => 2786,
                'blizzard_journal_encounter_id' => 2565,
            ],
            [
                'raid_id' => $amirdrassil->id,
                'slug' => 'fyrakk',
                'name' => 'Fyrakk',
                'order' => 9,
                'blizzard_dungeon_encounter_id' => 2677,
                'blizzard_journal_encounter_id' => 2519,
            ],
        ], ['raid_id', 'slug']);
    }
}
