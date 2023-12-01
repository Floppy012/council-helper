<?php

namespace App\Console\Commands;

use App\Models\AnalyzedReport;
use App\Models\Character;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class RebuildSupersededCommand extends Command
{
    protected $signature = 'report:rebuild-superseded';

    protected $description = 'Rebuilds superseded information';

    public function handle(): void
    {
        Character::query()
            ->chunk(10, fn (Collection $charChunk) => $charChunk
                ->each(function (Character $char) {
                    $char->analyzedReports()
                        ->selectRaw('DISTINCT ON (raid_difficulty, class_id, spec_id, race_id) *')
                        ->orderBy('raid_difficulty', 'DESC')
                        ->orderBy('class_id', 'DESC')
                        ->orderBy('spec_id', 'DESC')
                        ->orderBy('race_id', 'DESC')
                        ->orderBy('simulated_at', 'DESC')
                        ->each(function (AnalyzedReport $latest) use ($char) {
                            $exclude = [$latest->id];
                            /** @var AnalyzedReport|null $next */
                            $superseding = $latest;
                            do {
                                $curr = $char->analyzedReports()
                                    ->whereNull('superseding_id')
                                    ->whereIntegerNotInRaw('id', $exclude)
                                    ->where('raid_id', $superseding->raid_id)
                                    ->where('raid_difficulty', $superseding->raid_difficulty)
                                    ->where('class_id', $superseding->class_id)
                                    ->where('race_id', $superseding->race_id)
                                    ->where('spec_id', $superseding->spec_id)
                                    ->where('character_id', $superseding->character_id)
                                    ->orderBy('simulated_at', 'DESC')
                                    ->first();

                                $curr?->update(['superseding_id' => $superseding->id]);

                                $superseding = $curr;
                            } while ($superseding);

                        });
                })
            );
    }
}
