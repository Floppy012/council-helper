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
    public bool $loading;

    public function mount(ReportModel $report): void
    {
        $this->report = $report;
        $this->loading = (bool) $report->batch_id;
    }

    public function refreshLoadingState(): void
    {
        $this->loading = ReportModel::query()
            ->where('public_id', $this->report->public_id)
            ->whereNotNull('batch_id')
            ->exists();
    }

    public function render(): View
    {
        return view('livewire.report');
    }
}
