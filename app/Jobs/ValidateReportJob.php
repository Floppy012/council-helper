<?php

namespace App\Jobs;

use App\Enum\IngestJobType;
use App\Exceptions\ReportValidationException;
use App\Jobs\Trait\IsIngestJob;
use App\Models\Raid;
use App\Models\Report;
use App\Rules\EqualsRule;
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
        $validator = Validator::make($rawData->data, [
            'simbot.simType' => ['bail', 'required', new EqualsRule('droptimizer')],
            'simbot.meta.rawFormData.droptimizer.instance' => ['required', new In($instanceIds)],
            'sim.options.fight_style' => ['required', new EqualsRule('patchwerk')],
            'sim.options.max_time' => ['required', 'numeric', new EqualsRule(360)],
            'sim.options.desired_targets' => ['required', 'numeric', new EqualsRule(1)],
            'simbot.meta.rawFormData.droptimizer.difficulty' => ['required', 'in:raid-heroic-max-upgraded,raid-mythic-upgraded'],
        ], [
            'sim.options.max_time' => ':attribute must be 5 minutes',
            'simbot.meta.rawFormData.droptimizer.instance.in' => ':attribute not supported',
        ], [
            'simbot.simType' => 'Simulation Type',
            'sim.options.fight_style' => 'Fight style',
            'sim.options.max_time' => 'Fight duration',
            'sim.options.desired_targets' => 'Number of bosses',
            'simbot.meta.rawFormData.droptimizer.instance' => 'Raid instance',
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
