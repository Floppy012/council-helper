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
                'slug' => 'gnarlroot',
                'name' => 'Gnarlroot',
                'order' => 1,
                'blizzard_encounter_id' => 2820,
            ],
            [
                'raid_id' => $amirdrassil->id,
                'slug' => 'igira',
                'name' => 'Igira',
                'order' => 2,
                'blizzard_encounter_id' => 2709,
            ],
            [
                'raid_id' => $amirdrassil->id,
                'slug' => 'volcoross',
                'name' => 'Volcoross',
                'order' => 3,
                'blizzard_encounter_id' => 2737,
            ],
            [
                'raid_id' => $amirdrassil->id,
                'slug' => 'council-of-dreams',
                'name' => 'Council of Dreams',
                'order' => 4,
                'blizzard_encounter_id' => 2728,
            ],
            [
                'raid_id' => $amirdrassil->id,
                'slug' => 'larodar',
                'name' => 'Larodar',
                'order' => 5,
                'blizzard_encounter_id' => 2731,
            ],
            [
                'raid_id' => $amirdrassil->id,
                'slug' => 'nymue',
                'name' => 'Nymue',
                'order' => 6,
                'blizzard_encounter_id' => 2708,
            ],
            [
                'raid_id' => $amirdrassil->id,
                'slug' => 'smolderon',
                'name' => 'Smolderon',
                'order' => 7,
                'blizzard_encounter_id' => 2824,
            ],
            [
                'raid_id' => $amirdrassil->id,
                'slug' => 'tindral-sageswift',
                'name' => 'Tindral Sageswift',
                'order' => 8,
                'blizzard_encounter_id' => 2786,
            ],
            [
                'raid_id' => $amirdrassil->id,
                'slug' => 'fyrakk',
                'name' => 'Fyrakk',
                'order' => 9,
                'blizzard_encounter_id' => 2677,
            ],
        ], ['raid_id', 'slug']);
    }
}
