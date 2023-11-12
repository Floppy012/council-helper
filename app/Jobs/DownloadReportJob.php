<?php

namespace App\Jobs;

use App\Enum\IngestJobType;
use App\Jobs\Trait\IsIngestJob;
use App\Models\Report;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Throwable;

class DownloadReportJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, IsIngestJob, Queueable, SerializesModels;

    public int $retries = 3;

    public function __construct(
        protected readonly Report $report
    ) {
        $this->type = IngestJobType::DOWNLOAD_REPORT;
        $this->create();
    }

    public function handle(): void
    {
        $this->begin();
        $response = Http::withUserAgent('council-helper')
            ->acceptJson()
            ->maxRedirects(3)
            ->throw()
            ->get($this->report->url.'/data.json');

        sleep(5);

        $this->report->update([
            'raw' => $response->json(),
        ]);
        $this->finish(true);
    }

    public function failed(Throwable $exception): void
    {
        $this->finish(false);
    }
}
