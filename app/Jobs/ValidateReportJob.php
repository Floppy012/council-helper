<?php

namespace App\Jobs;

use App\Enum\IngestJobType;
use App\Exceptions\ReportValidationException;
use App\Jobs\Trait\IsIngestJob;
use App\Models\Raid;
use App\Models\Report;
use App\Rules\EqualsRule;
use App\Rules\UpgradeLevelRule;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\In;
use Throwable;

class ValidateReportJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, IsIngestJob, Queueable, SerializesModels;

    public int $retries = 0;

    public function __construct(
        protected readonly Report $report,
    ) {
        $this->type = IngestJobType::VALIDATE_REPORT;
        $this->create();
    }

    public function handle(): void
    {
        $this->begin();
        $instanceIds = Raid::pluck('blizzard_instance_id')->all();
        $rawData = $this->report->rawData;
        $validator = Validator::make(json_decode(json_encode($rawData->data), true), [
            'simbot.simType' => ['bail', 'required', new EqualsRule('droptimizer')],
            'simbot.meta.rawFormData.droptimizer.instance' => ['required', new In($instanceIds)],
            'sim.options.fight_style' => ['required', new EqualsRule('patchwerk')],
            'sim.options.max_time' => ['required', 'numeric', new EqualsRule(300)],
            'sim.options.desired_targets' => ['required', 'numeric', new EqualsRule(1)],
            'simbot.meta.rawFormData.droptimizer.difficulty' => ['required', 'regex:/^raid-(?:lfr|normal|heroic|mythic)$/'],
            'simbot.meta.ptr' => ['required', new EqualsRule(false)],
            'simbot.meta.customApl' => ['required', new EqualsRule(false)],
            'simbot.meta.expertMode' => ['required', new EqualsRule(false)],
            'simbot.meta.rawFormData.simcVersion' => ['required', new EqualsRule('nightly', skip: config('app.env') !== 'production')],
            'simbot.meta.rawFormData.iterations' => ['required', new EqualsRule('smart')],
            'simbot.meta.rawFormData.droptimizer.upgradeLevel' => ['required', new UpgradeLevelRule()],
            // Buffs
            'simbot.meta.optimalRaid' => ['required', new EqualsRule(true)],
            'simbot.meta.bloodlust' => ['required', new EqualsRule(true)],
            'simbot.meta.arcaneIntellect' => ['required', new EqualsRule(true)],
            'simbot.meta.fortitude' => ['required', new EqualsRule(true)],
            'simbot.meta.battleShout' => ['required', new EqualsRule(true)],
            'simbot.meta.mysticTouch' => ['required', new EqualsRule(true)],
            'simbot.meta.chaosBrand' => ['required', new EqualsRule(true)],
            'simbot.meta.skyfury' => ['required', new EqualsRule(true)],
            'simbot.meta.markOfTheWild' => ['required', new EqualsRule(true)],
            'simbot.meta.huntersMark' => ['required', new EqualsRule(true)],
            'simbot.meta.bleeding' => ['required', new EqualsRule(true)],
            'simbot.meta.powerInfusion' => ['required', new EqualsRule(false)],
            // Seasonal stuff
            //TODO
        ], [
            'sim.options.max_time' => ':attribute must be 5 minutes',
            'simbot.meta.rawFormData.droptimizer.instance.in' => ':attribute not supported',
            'simbot.meta.rawFormData.droptimizer.difficulty.regex' => ':attribute is invalid',
            'simbot.meta.rawFormData.simcVersion' => 'SimC Version must be "Nightly"',
            'simbot.meta.ptr' => 'PTR Simulations are not allowed',
            'simbot.meta.customApl' => 'Custom APLs are not allowed',
            'simbot.meta.expertMode' => 'Expert Mode is not allowed',

            // Buffs
            'simbot.meta.optimalRaid' => 'Optimal Raid Buffs must be active',
            'simbot.meta.bloodlust' => 'Bloodlust must be active',
            'simbot.meta.arcaneIntellect' => 'Arcane Intellect must be active',
            'simbot.meta.fortitude' => 'PW: Fortitude must be active',
            'simbot.meta.battleShout' => 'Battle Shout must be active',
            'simbot.meta.mysticTouch' => 'Mystic Touch must be active',
            'simbot.meta.chaosBrand' => 'Chaos Brand must be active',
            'simbot.meta.skyfury' => 'Skyfury must be active',
            'simbot.meta.markOfTheWild' => 'Mark of the Wild must be active',
            'simbot.meta.huntersMark' => 'Hunters Mark must be active',
            'simbot.meta.bleeding' => 'Bleeding must be active',
            'simbot.meta.powerInfusion' => 'Power Infusion must not be active',
            'simbot.meta.blueSilkenLining' => 'Blue Silken Lining Uptime must be 40%',
            'simbot.meta.corruptingRageUptime' => 'Corrupting Rage Uptime must be 80%',

            // Seasonal
            'simbot.meta.balefireBranchRngType' => 'Balefire Branch Uptime must be set to "Default"',
            'simbot.meta.whisperingIncarnateIconRoles' => 'Whispering Incarnate Icon Roles must be "DPS + Tank + Healer"',
            'simbot.meta.ominousChromaticEssenceAllies' => 'Ominous Chromatic Essence Allies must be "All Ally Buffs"',
        ], [
            'simbot.simType' => 'Simulation Type',
            'sim.options.fight_style' => 'Fight style',
            'sim.options.max_time' => 'Fight duration',
            'sim.options.desired_targets' => 'Number of bosses',
            'simbot.meta.rawFormData.droptimizer.instance' => 'Raid instance',
            'simbot.meta.rawFormData.droptimizer.difficulty' => 'Raid difficulty',
        ]);

        if ($validator->fails()) {
            throw new ReportValidationException($validator->errors()->getMessages());
        }

        $this->finish(true);
    }

    public function failed(Throwable $throwable): void
    {
        if ($throwable instanceof ReportValidationException) {
            $this->finish(false, $throwable->errors);
        } else {
            $this->finish(false);
        }
    }
}
