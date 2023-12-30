<?php

namespace App\Livewire;

use App\Enum\CharacterClass;
use App\Enum\CharacterRace;
use App\Enum\CharacterSpec;
use App\Models\AnalyzedReport;
use App\Models\Character;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class CharacterProfile extends Component
{
    #[Locked]
    public Character $character;

    #[Locked]
    public CharacterRace $lastRace;

    #[Locked]
    public CharacterClass $lastClass;

    public function mount(): void
    {
        $latestReports = $this->character->analyzedReports()
            ->orderBy('created_at', 'DESC')
            ->first();

        if ($latestReport = $latestReports->first()) {
            $this->lastRace = $latestReport->race_id;
            $this->lastClass = $latestReport->class_id;
        }

    }

    /**
     * @return Collection<AnalyzedReport>
     */
    #[Computed]
    public function analyzedReports(): Collection
    {
        return $this->character->analyzedReports()->select([
            'spec_id', 'simulated_at', 'dps_mean', 'raid_difficulty', 'raid_id', 'report_id',
        ])
            ->with(['raid', 'report'])
            ->limit(100)
            ->get();
    }

    #[Computed]
    public function dpsHistoryDatasets(): array
    {
        return $this->analyzedReports()
            ->groupBy('spec_id.value')
            ->map(function (Collection $reports, int $specId) {
                return [
                    'label' => CharacterSpec::from($specId)->name(),
                    'data' => $reports->map(fn (AnalyzedReport $report) => [
                        'x' => $report->simulated_at->toIso8601String(),
                        'y' => $report->dps_mean,
                    ])->all(),
                ];
            })
            ->values()
            ->all();
    }

    public function render()
    {
        return view('livewire.character-profile');
    }
}
