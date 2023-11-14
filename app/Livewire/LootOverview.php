<?php

namespace App\Livewire;

use App\Enum\RaidDifficulty;
use App\Models\Encounter;
use App\Models\ItemSimResult;
use App\Models\Raid;
use App\Models\Team;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Session;

class LootOverview extends Component
{
    #[Locked]
    public Raid $raid;

    #[Locked]
    public Encounter $encounter;

    public RaidDifficulty $difficulty;

    public ?string $filterTeamId = null;

    /** @var Collection<integer, Collection<integer, ItemSimResult>> */
    #[Locked]
    public Collection $simResults;

    public function mount(Raid $raid, Encounter $encounter)
    {
        $this->raid = $raid;
        $this->encounter = $encounter;

        $this->difficulty = Session::get('difficulty', RaidDifficulty::NORMAL);

        $teamId = Session::get('filterTeamId');
        if ($teamId && Team::where('public_id', $teamId)->exists()) {
            $this->filterTeamId = $teamId;
        }

        $this->updateSimResults();
    }

    #[Computed]
    public function loot(): Collection
    {
        return $this->encounter->loot()->where('catalyst', false)->orderBy('name')->get();
    }

    public function updated(string $property): void
    {
        if ($property === 'difficulty') {
            Session::put('difficulty', $this->difficulty);
            $this->updateSimResults();
        }

        if ($property === 'filterTeamId') {
            if ($this->filterTeamId === '') {
                $this->filterTeamId = null;
            }
            Session::put('filterTeamId', $this->filterTeamId);
            $this->updateSimResults();
        }
    }

    protected function updateSimResults(): void
    {
        $query = ItemSimResult::from('item_sim_results as isr')
            ->selectRaw('DISTINCT ON (isr.item_id, char.id, ar.spec_id) isr.*')
            ->join('analyzed_reports as ar', 'ar.id', '=', 'isr.analyzed_report_id')
            ->join('characters as char', 'char.id', '=', 'ar.character_id')
            ->leftJoin('characters_teams as c2t', 'c2t.character_id', '=', 'char.id')
            ->leftJoin('teams', 'teams.id', '=', 'c2t.team_id')
            ->where('isr.encounter_id', $this->encounter->id)
            ->where('ar.raid_difficulty', $this->difficulty->value)
            ->where('ar.simulated_at', '>', now()->subWeek());

        if ($this->filterTeamId) {
            $query = $query->where('teams.public_id', $this->filterTeamId);
        }

        $results = $query
            ->orderBy('isr.item_id', 'DESC')
            ->orderBy('char.id', 'DESC')
            ->orderBy('ar.spec_id', 'DESC')
            ->orderBy('ar.created_at', 'DESC')
            ->orderBy('isr.median', 'DESC')
            ->with(['analyzedReport.character', 'item.catalystSourceItems'])
            ->get();

        $grouped = Collection::empty();
        $itemIds = $this->loot()->pluck('id');

        foreach ($results as $result) {
            if ($itemIds->contains($result->item_id)) {
                $groupItemId = $result->item_id;
            } else {
                $sourceItem = $result->item->catalystSourceItems->whereIn('id', $itemIds)->first();
                $groupItemId = $sourceItem->id;
            }

            $group = $grouped->get($groupItemId, Collection::empty());
            $group->add($result);
            $grouped->put($groupItemId, $group);
        }

        $grouped = $grouped->map(fn (Collection $col) => $col->sortByDesc('mean_gain'));

        $this->simResults = $grouped;
    }

    public function render()
    {
        return view('livewire.loot-overview')
            ->with('teams', Team::all(['name', 'public_id']));
    }
}
