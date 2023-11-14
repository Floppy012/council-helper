<?php

namespace App\Jobs;

use App\Enum\IngestJobType;
use App\Jobs\Trait\IsIngestJob;
use App\Models\AnalyzedReport;
use App\Models\Character;
use App\Models\Encounter;
use App\Models\Item;
use App\Models\ItemSimResult;
use App\Models\Raid;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AnalyzeReportJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, IsIngestJob, Queueable, SerializesModels;

    public function __construct(
        protected readonly Report $report
    ) {
        $this->type = IngestJobType::ANALYZE_REPORT;
        $this->create();
    }

    public function handle(): void
    {
        $this->begin();
        $data = $this->report->rawData->data;

        $character = $this->syncCharacter($data);
        $rawFormData = $data->simbot->meta->rawFormData;
        $raid = Raid::where('blizzard_instance_id', $rawFormData->droptimizer->instance)
            ->firstOrFail();
        $charInfo = $rawFormData->character;
        $raidDps = $data->sim->statistics->raid_dps;
        $specId = ($charInfo->talents ?? false)
            ? collect($charInfo->talents)->first(fn (object $talent) => $talent->selected ?? false)->spec->id
            : $charInfo->v2->profile->active_spec->id;
        $analyzedReport = $character->analyzedReports()->create([
            'report_id' => $this->report->id,
            'raid_id' => $raid->id,
            'raid_difficulty' => preg_replace('/^raid-(lfr|normal|heroic|mythic).*$/', '$1', $rawFormData->droptimizer->difficulty),
            'class_id' => $charInfo->class,
            'race_id' => $charInfo->race,
            'spec_id' => $specId,
            'dps_mean' => $raidDps->mean,
            'dps_median' => $raidDps->median,
            'dps_min' => $raidDps->min,
            'dps_max' => $raidDps->max,
            'simulated_at' => Carbon::createFromTimestampMs($data->simbot->jobSubmitted),
        ]);

        $this->syncItemLibrary($analyzedReport, $data);
        $this->syncSimResults($analyzedReport, $data);
    }

    protected function syncCharacter(object $data): Character
    {
        $charInfo = $data->simbot->meta->rawFormData->character;
        $region = $charInfo->region ?? $data->simbot->meta->rawFormData->armory?->region;
        $data = [
            'name' => $charInfo->name,
            'realm' => Str::lower($charInfo->realm),
            'region' => $region,
        ];

        return Character::updateOrCreate($data, $data);
    }

    protected function syncItemLibrary(AnalyzedReport $analyzedReport, object $data): void
    {
        $items = collect($data->simbot->meta->itemLibrary);

        // Fetch encounters for this report
        $availableEncounters = $analyzedReport->raid->encounters->mapWithKeys(fn (Encounter $encounter) => [
            $encounter->blizzard_journal_encounter_id ?? -1 => $encounter,
        ]);

        // Upsert all catalyst items
        $catalystItemsRaw = $items->filter(fn (object $item) => ($item->tags ?? false) && $item->tags[0] === 'catalyst');
        $upsertCatalystItems = $catalystItemsRaw->map(fn (object $item) => $this->createItemUpsert($item, $availableEncounters));
        Item::upsert($upsertCatalystItems->all(), ['encounter_id', 'blizzard_item_id']);

        // Upsert all non-catalyst items
        $catalystItems = Item::whereIntegerInRaw('blizzard_item_id', $catalystItemsRaw->pluck('id'))
            ->get();

        $catalystItemMap = $catalystItemsRaw->mapWithKeys(fn (object $rawItem) => [
            $rawItem->sourceItem->id => $catalystItems
                ->where('encounter_id', ($availableEncounters->get($rawItem->encounterId) ?? $availableEncounters->get(-1))->id)
                ->where('blizzard_item_id', $rawItem->id)
                ->first(),
        ]);

        $normalItemsRaw = $items->reject(fn (object $item) => ($item->tags ?? false) && $item->tags[0] === 'catalyst');
        $upsertNormalItems = $normalItemsRaw->map(fn (object $item) => $this->createItemUpsert($item, $availableEncounters));
        Item::upsert($upsertNormalItems->all(), ['encounter_id', 'blizzard_item_id']);

        $normalItems = Item::whereIntegerInRaw('blizzard_item_id', $normalItemsRaw->pluck('id'))
            ->get();

        $upsertCatalystRelations = $normalItems->map(fn (Item $normal) => [
            'item_id' => $normal->id,
            'catalyst_item_id' => $catalystItemMap->get($normal->blizzard_item_id)?->id,
        ])->filter(fn (array $arr) => (bool) $arr['catalyst_item_id']);

        DB::table('catalyst_items_2_items')
            ->upsert($upsertCatalystRelations->all(), ['item_id', 'catalyst_item_id']);
    }

    protected function syncSimResults(AnalyzedReport $analyzedReport, object $data): void
    {
        $profilesets = collect($data->sim->profilesets->results);

        $blizzardItemIds = $profilesets->map(fn (object $set) => intval(explode('/', $set->name)[3]));
        $encounters = $analyzedReport->raid->encounters->mapWithKeys(fn (Encounter $encounter) => [
            $encounter->blizzard_journal_encounter_id ?? -1 => $encounter,
        ]);

        /** @var Collection<int, Item> $items */
        $items = Item::whereIntegerInRaw('encounter_id', $encounters->pluck('id'))
            ->whereIntegerInRaw('blizzard_item_id', $blizzardItemIds)
            ->get();

        /*
         * Raidbots simulates the same items for different bossfights (e.g. catalysed chest piece). Due to variances
         * in the simulation the numbers vary. Raidbots always shows the highest value of all sims of the same item even
         * if the data says otherwise. To prevent confusion, we also have to do that.
         */
        $profilesets->each(function (object $set) use ($profilesets) {
            $nameParts = explode('/', $set->name);
            $blizzItemId = $nameParts[3];
            $slot = $nameParts[6];

            $dupeSims = $profilesets->filter(function (object $other) use ($blizzItemId, $slot) {
                $nameParts = explode('/', $other->name);

                return $nameParts[3] === $blizzItemId && $nameParts[6] === $slot;
            });

            if ($dupeSims->count() <= 1) {
                return;
            }

            $highest = $dupeSims->sort(function (object $a, object $b) {
                if ($a->mean > $b->mean) {
                    return -1;
                }

                if ($a->mean < $b->mean) {
                    return 1;
                }

                return 0;
            })->first();

            $dupeSims->each(function (object $other) use ($highest) {
                $other->mean = $highest->mean;
                $other->min = $highest->min;
                $other->max = $highest->max;
                $other->stddev = $highest->stddev;
                $other->mean_stddev = $highest->mean_stddev;
                $other->mean_error = $highest->mean_error;
                $other->median = $highest->median;
                $other->first_quartile = $highest->first_quartile;
                $other->third_quartile = $highest->third_quartile;
            });
        });

        $upserts = $profilesets->map(function (object $set) use ($analyzedReport, $encounters, $items) {
            $nameParts = explode('/', $set->name);
            $encounter = $encounters->get($nameParts[1], $encounters->get(-1));
            $item = $items
                ->where('encounter_id', $encounter->id)
                ->where('blizzard_item_id', intval($nameParts[3]))
                ->first();

            return [
                'analyzed_report_id' => $analyzedReport->id,
                'item_id' => $item->id,
                'encounter_id' => $encounter->id,
                'sim_slot' => $nameParts[6],
                'profileset_special' => $nameParts[7] ?? null,
                'profileset_name' => $set->name,
                'mean' => $set->mean,
                'median' => $set->median,
                'min' => $set->min,
                'max' => $set->max,
                'mean_gain' => $set->mean - $analyzedReport->dps_mean,
                'median_gain' => $set->median - $analyzedReport->dps_median,
                'min_gain' => $set->min - $analyzedReport->dps_min,
                'max_gain' => $set->max - $analyzedReport->dps_max,
            ];
        });

        //        $dupeUpserts = $upserts->map(function (array $upsert) use ($upserts) {
        //            return $upserts->filter(function (array $other) use ($upsert) {
        //                return $upsert['analyzed_report_id'] === $other['analyzed_report_id']
        //                    && $upsert['item_id'] === $other['item_id']
        //                    && $upsert['encounter_id'] === $other['encounter_id']
        //                    && $upsert['sim_slot'] === $other['sim_slot']
        //                    && $upsert['profileset_special'] === $other['profileset_special'];
        //            });
        //        })->filter(fn (Collection $col) => $col->count() > 1);

        ItemSimResult::upsert($upserts->all(), ['analyzed_report_id', 'item_id', 'encounter_id', 'sim_slot', 'profileset_special']);
    }

    /**
     * @param  Collection<int, Encounter>  $encounters
     */
    protected function createItemUpsert(object $item, Collection $encounters): array
    {
        return [
            'encounter_id' => ($encounters->get($item->encounterId) ?? $encounters->get(-1))->id,
            'blizzard_item_id' => $item->id,
            'name' => $item->name,
            'icon_slug' => $item->icon,
            'catalyst' => ($item->tags ?? false) && $item->tags[0] === 'catalyst',
        ];
    }
}
