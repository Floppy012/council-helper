<?php

namespace App\Jobs;

use App\Models\Report;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class DownloadReportJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $retries = 3;

    public function __construct(
        private readonly Report $report
    ) {
    }

    public function handle(): void
    {
        $response = Http::withUserAgent('council-helper')
            ->acceptJson()
            ->maxRedirects(3)
            ->throw()
            ->get($this->report->url.'/data.json');

        $this->report->update([
            'raw' => $response->json(),
        ]);
    }
}
