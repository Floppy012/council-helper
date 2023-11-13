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
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

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
        $analyzedReport = $character->analyzedReports()->create([
            'report_id' => $this->report->id,
            'raid_id' => $raid->id,
            'raid_difficulty' => preg_replace('/^raid-(lfr|normal|heroic|mythic).*$/', '$1', $rawFormData->droptimizer->difficulty),
            'class_id' => $charInfo->class,
            'race_id' => $charInfo->race,
            'spec_id' => collect($charInfo->talents)->first(fn (object $talent) => $talent->selected ?? false)->spec->id,
            'dps_mean' => $raidDps->mean,
            'dps_median' => $raidDps->median,
            'dps_min' => $raidDps->min,
            'dps_max' => $raidDps->max,
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
            'realm' => $charInfo->realm,
            'region' => $region,
        ];

        return Character::updateOrCreate($data, $data);
    }

    protected function syncItemLibrary(AnalyzedReport $analyzedReport, object $data): void
    {
        $items = collect($data->simbot->meta->itemLibrary)->mapWithKeys(fn (object $item) => [$item->id => $item]);

        // Fetch encounters for this report
        $availableEncounters = $analyzedReport->raid->encounters->mapWithKeys(fn (Encounter $encounter) => [
            $encounter->blizzard_journal_encounter_id ?? -1 => $encounter,
        ]);

        // Upsert all non-catalyst items
        $upsertNormalItems = $items->reject(fn (object $item) => ($item->tags ?? false) && $item->tags[0] === 'catalyst')
            ->map(fn (object $item) => $this->createItemUpsert($item, $availableEncounters));

        Item::upsert($upsertNormalItems->all(), ['encounter_id', 'blizzard_item_id']);

        // Upsert all catalyst items
        $catalystItems = $items->filter(fn (object $item) => ($item->tags ?? false) && $item->tags[0] === 'catalyst');

        $sourceItems = Item::whereIntegerInRaw('blizzard_item_id', $catalystItems->pluck('sourceItem.id'))
            ->get()
            ->mapWithKeys(fn (Item $item) => [$item->blizzard_item_id => $item]);

        $upsertCatalystItems = $catalystItems->map(fn (object $item) => $this->createItemUpsert($item, $availableEncounters, $sourceItems->get($item->sourceItem->id))
        );

        Item::upsert($upsertCatalystItems->all(), ['encounter_id', 'blizzard_item_id']);
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
            ->get()
            ->keyBy('blizzard_item_id');

        $upserts = $profilesets->map(function (object $set) use ($analyzedReport, $encounters, $items) {
            $nameParts = explode('/', $set->name);
            $item = $items->get(intval($nameParts[3]));
            $encounter = $encounters->get($nameParts[1], $encounters->get(-1));

            return [
                'analyzed_report_id' => $analyzedReport->id,
                'item_id' => $item->id,
                'encounter_id' => $encounter->id,
                'sim_slot' => $nameParts[6],
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

        ItemSimResult::upsert($upserts->all(), ['analyzed_report_id', 'item_id', 'encounter_id', 'sim_slot']);
    }

    /**
     * @param  Collection<int, Encounter>  $encounters
     */
    protected function createItemUpsert(object $item, Collection $encounters, Item $catalystSourceItem = null): array
    {
        return [
            'encounter_id' => ($encounters->get($item->encounterId) ?? $encounters->get(-1))->id,
            'blizzard_item_id' => $item->id,
            'name' => $item->name,
            'icon_slug' => $item->icon,
            'catalyst' => (bool) $catalystSourceItem,
            'catalyst_source_item_id' => $catalystSourceItem?->id,
        ];
    }
}
