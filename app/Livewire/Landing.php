<?php

namespace App\Livewire;

use App\Jobs\DownloadReportJob;
use App\Jobs\FinalizeReportJob;
use Bus;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Landing extends Component
{
    #[Rule(
        ['required', 'string', 'url', 'active_url', 'regex:/^https?:\/\/(?:www\.)?raidbots.com\/simbot\/report\/[a-zA-Z0-9]+$/'],
        as: 'report url'
    )]
    public string $reportUrl;

    public function submit()
    {
        $this->validate();

        $report = \App\Models\Report::create([
            'url' => $this->reportUrl,
        ])->fresh();

        $batch = Bus::batch([
            [
                new DownloadReportJob($report),
                new FinalizeReportJob($report),
            ],
        ])->dispatchAfterResponse();

        $report->update([
            'batch_id' => $batch->id,
        ]);

        return redirect()
            ->to(route('report', ['report' => $report->public_id]));
    }

    public function render(): View
    {
        return view('livewire.landing');
    }
}
