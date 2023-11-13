<?php

namespace App\Livewire;

use App\Models\IngestJob;
use App\Models\Report as ReportModel;
use Bus;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use RuntimeException;

class ReportLoading extends Component
{
    #[Locked]
    public ReportModel $report;

    #[Locked]
    public int $progress = 0;

    #[Locked]
    public bool $failed = false;

    #[Locked]
    public array $jobs = [];

    public function mount(ReportModel $report): void
    {
        $this->report = $report;
        $this->refresh();
    }

    public function refresh(): void
    {
        $this->jobs = $this->report->ingestJobs->map(fn (IngestJob $job) => [
            'type' => $job->type,
            'order' => $job->type->order(),
            'status' => $job->status,
            'errors' => collect($job->errors)->flatten()->all(),
        ])->sortBy('order')->all();

        if (! $this->report->batch_id) {
            $this->progress = 100;
            $this->failed = false;

            return;
        }

        $batch = Bus::findBatch($this->report->batch_id);
        if (! $batch) {
            report(new RuntimeException("Batch {$this->report->batch_id} for {$this->report->public_id} not found"));

            return;
        }

        $this->failed = $batch->hasFailures();
        $this->progress = $this->failed ? 100 : $batch->progress();
    }

    public function render(): View
    {
        return view('livewire.report-loading');
    }
}
