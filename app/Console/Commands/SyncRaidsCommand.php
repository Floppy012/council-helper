<?php

namespace App\Console\Commands;

use App\Models\Encounter;
use App\Models\Raid;
use Illuminate\Console\Command;

/**
 * @see https://wowpedia.fandom.com/wiki/JournalEncounterID
 * @see https://wago.tools/db2/JournalEncounter
 * @see https://wago.tools/db2/DungeonEncounter
 */
class SyncRaidsCommand extends Command
{
    protected $signature = 'sync:raids';

    protected $description = 'Synchronizes raids in the database (without deletion)';

    public function handle(): void
    {
        Raid::upsert([
            [
                'slug' => 'nerubar-palace',
                'name' => 'Nerub\'ar Palace',
                'blizzard_instance_id' => 1273,
            ],
            [
                'slug' => 'amirdrassil',
                'name' => 'Amirdrassil',
                'blizzard_instance_id' => 1207,
            ],
            [
                'slug' => 'aberrus',
                'name' => 'Aberrus',
                'blizzard_instance_id' => 1208,
            ],
            [
                'slug' => 'vault-of-the-incarnates',
                'name' => 'Vault of the Incarnates',
                'blizzard_instance_id' => 1200,
            ],
        ], ['slug']);

        $this->syncNerubarPalaceBosses();
        $this->syncAmirdrassilBosses();
        $this->syncAberrusBosses();
        $this->syncVotiBosses();
    }

    private function syncNerubarPalaceBosses(): void
    {
        $palace = Raid::where('slug', 'nerubar-palace')->firstOrFail();
        Encounter::upsert([
            [
                'raid_id' => $palace->id,
                'slug' => 'trash',
                'name' => 'Trash',
                'order' => 0,
                'blizzard_dungeon_encounter_id' => null,
                'blizzard_journal_encounter_id' => null,
            ],
            [
                'raid_id' => $palace->id,
                'slug' => 'ulgrax',
                'name' => 'Ulgrax',
                'order' => 1,
                'blizzard_dungeon_encounter_id' => 2902,
                'blizzard_journal_encounter_id' => 2607,
            ],
            [
                'raid_id' => $palace->id,
                'slug' => 'bloodhound-horror',
                'name' => 'The Bloodhound Horror',
                'order' => 2,
                'blizzard_dungeon_encounter_id' => 2917,
                'blizzard_journal_encounter_id' => 2611,
            ],
            [
                'raid_id' => $palace->id,
                'slug' => 'sikran',
                'name' => 'Sikran',
                'order' => 3,
                'blizzard_dungeon_encounter_id' => 2898,
                'blizzard_journal_encounter_id' => 2599,
            ],
            [
                'raid_id' => $palace->id,
                'slug' => 'rashanan',
                'name' => 'Rasha\'nan',
                'order' => 4,
                'blizzard_dungeon_encounter_id' => 2918,
                'blizzard_journal_encounter_id' => 2609,
            ],
            [
                'raid_id' => $palace->id,
                'slug' => 'ovinax',
                'name' => 'Broodtwister Ovi\'nax',
                'order' => 5,
                'blizzard_dungeon_encounter_id' => 2919,
                'blizzard_journal_encounter_id' => 2612,
            ],
            [
                'raid_id' => $palace->id,
                'slug' => 'kyveza',
                'name' => 'Nexus-Princess Ky\'veza',
                'order' => 6,
                'blizzard_dungeon_encounter_id' => 2920,
                'blizzard_journal_encounter_id' => 2601,
            ],
            [
                'raid_id' => $palace->id,
                'slug' => 'silken-court',
                'name' => 'The Silken Court',
                'order' => 7,
                'blizzard_dungeon_encounter_id' => 2921,
                'blizzard_journal_encounter_id' => 2608,
            ],
            [
                'raid_id' => $palace->id,
                'slug' => 'ansurek',
                'name' => 'Queen Ansurek',
                'order' => 8,
                'blizzard_dungeon_encounter_id' => 2922,
                'blizzard_journal_encounter_id' => 2602,
            ],
        ], ['raid_id', 'slug']);
    }

    /**
     * DF S3
     */
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

    /**
     * DF S2
     */
    private function syncAberrusBosses(): void
    {
        $aberrus = Raid::where('slug', 'aberrus')->firstOrFail();

        Encounter::upsert([
            [
                'raid_id' => $aberrus->id,
                'slug' => 'trash',
                'name' => 'Trash',
                'order' => 0,
                'blizzard_dungeon_encounter_id' => null,
                'blizzard_journal_encounter_id' => null,
            ],
            [
                'raid_id' => $aberrus->id,
                'slug' => 'kazzara',
                'name' => 'Kazzara',
                'order' => 1,
                'blizzard_dungeon_encounter_id' => 2688,
                'blizzard_journal_encounter_id' => 2522,
            ],
            [
                'raid_id' => $aberrus->id,
                'slug' => 'amalgamation-chamber',
                'name' => 'Amalgamation Chamber',
                'order' => 2,
                'blizzard_dungeon_encounter_id' => 2687,
                'blizzard_journal_encounter_id' => 2529,
            ],
            [
                'raid_id' => $aberrus->id,
                'slug' => 'zaqali-assault',
                'name' => 'Zaqali Assault',
                'order' => 3,
                'blizzard_dungeon_encounter_id' => 2682,
                'blizzard_journal_encounter_id' => 2524,
            ],
            [
                'raid_id' => $aberrus->id,
                'slug' => 'forgotten-experiments',
                'name' => 'Forgotten Experiments',
                'order' => 4,
                'blizzard_dungeon_encounter_id' => 2693,
                'blizzard_journal_encounter_id' => 2530,
            ],
            [
                'raid_id' => $aberrus->id,
                'slug' => 'rashok',
                'name' => 'Rashok',
                'order' => 5,
                'blizzard_dungeon_encounter_id' => 2680,
                'blizzard_journal_encounter_id' => 2525,
            ],
            [
                'raid_id' => $aberrus->id,
                'slug' => 'zskarn',
                'name' => 'Zskarn',
                'order' => 6,
                'blizzard_dungeon_encounter_id' => 2689,
                'blizzard_journal_encounter_id' => 2532,
            ],
            [
                'raid_id' => $aberrus->id,
                'slug' => 'magmorax',
                'name' => 'Magmorax',
                'order' => 7,
                'blizzard_dungeon_encounter_id' => 2683,
                'blizzard_journal_encounter_id' => 2527,
            ],
            [
                'raid_id' => $aberrus->id,
                'slug' => 'echo-of-neltharion',
                'name' => 'Echo of Neltharion',
                'order' => 8,
                'blizzard_dungeon_encounter_id' => 2684,
                'blizzard_journal_encounter_id' => 2523,
            ],
            [
                'raid_id' => $aberrus->id,
                'slug' => 'sarkareth',
                'name' => 'Sarkareth',
                'order' => 9,
                'blizzard_dungeon_encounter_id' => 2685,
                'blizzard_journal_encounter_id' => 2520,
            ],
        ], ['raid_id', 'slug']);
    }

    /**
     * DF S1
     */
    private function syncVotiBosses(): void
    {
        $voti = Raid::where('slug', 'vault-of-the-incarnates')->firstOrFail();

        Encounter::upsert([
            [
                'raid_id' => $voti->id,
                'slug' => 'trash',
                'name' => 'Trash',
                'order' => 0,
                'blizzard_dungeon_encounter_id' => null,
                'blizzard_journal_encounter_id' => null,
            ],
            [
                'raid_id' => $voti->id,
                'slug' => 'eranog',
                'name' => 'Eranog',
                'order' => 1,
                'blizzard_dungeon_encounter_id' => 2587,
                'blizzard_journal_encounter_id' => 2480,
            ],
            [
                'raid_id' => $voti->id,
                'slug' => 'primal-council',
                'name' => 'Primal Council',
                'order' => 2,
                'blizzard_dungeon_encounter_id' => 2590,
                'blizzard_journal_encounter_id' => 2486,
            ],
            [
                'raid_id' => $voti->id,
                'slug' => 'terros',
                'name' => 'Terros',
                'order' => 3,
                'blizzard_dungeon_encounter_id' => 2639,
                'blizzard_journal_encounter_id' => 2500,
            ],
            [
                'raid_id' => $voti->id,
                'slug' => 'sennarth',
                'name' => 'Sennarth',
                'order' => 4,
                'blizzard_dungeon_encounter_id' => 2592,
                'blizzard_journal_encounter_id' => 2482,
            ],
            [
                'raid_id' => $voti->id,
                'slug' => 'kurog-grimmtotem',
                'name' => 'Kurog Grimmtotem',
                'order' => 5,
                'blizzard_dungeon_encounter_id' => 2605,
                'blizzard_journal_encounter_id' => 2491,
            ],
            [
                'raid_id' => $voti->id,
                'slug' => 'dathea',
                'name' => 'Dathea',
                'order' => 6,
                'blizzard_dungeon_encounter_id' => 2635,
                'blizzard_journal_encounter_id' => 2502,
            ],
            [
                'raid_id' => $voti->id,
                'slug' => 'broodkeeper-diurna',
                'name' => 'Broodkeeper Diurna',
                'order' => 7,
                'blizzard_dungeon_encounter_id' => 2614,
                'blizzard_journal_encounter_id' => 2493,
            ],
            [
                'raid_id' => $voti->id,
                'slug' => 'raszageth',
                'name' => 'Raszageth',
                'order' => 8,
                'blizzard_dungeon_encounter_id' => 2607,
                'blizzard_journal_encounter_id' => 2499,
            ],
        ], ['raid_id', 'slug']);
    }
}
