<?php

namespace App\Livewire;

use App\Models\Report as ReportModel;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Report extends Component
{
    #[Locked]
    public ReportModel $report;

    #[Locked]
    public ?string $raidSlug;

    public function mount(ReportModel $report): void
    {
        $this->report = $report;
        $this->refresh();
    }

    public function refresh(): void
    {
        $this->report = ReportModel::query()
            ->where('public_id', $this->report->public_id)
            ->first();

        $analyzed = $this->report->analyzedReport()->with('raid')->first();
        if ($analyzed) {
            $this->raidSlug = $analyzed->raid->slug;
        }
    }

    public function render(): View
    {
        return view('livewire.report');
    }
}
