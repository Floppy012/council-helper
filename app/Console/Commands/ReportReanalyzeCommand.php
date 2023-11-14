<?php

namespace App\Console\Commands;

use App\Enum\IngestJobStatus;
use App\Enum\IngestJobType;
use App\Jobs\AnalyzeReportJob;
use App\Models\Report;
use Bus;
use Illuminate\Console\Command;
use Throwable;

class ReportReanalyzeCommand extends Command
{
    protected $signature = 'report:reanalyze';

    protected $description = 'Re-Analyzes all reports';

    /**
     * @throws Throwable
     */
    public function handle(): void
    {
        $jobs = Report::query()
            ->selectRaw('DISTINCT ON (reports.id) reports.*')
            ->join('report_raw_data as rrd', 'rrd.report_id', '=', 'reports.id')
            ->join('ingest_jobs as ij', 'ij.report_id', '=', 'reports.id')
            ->where('ij.type', IngestJobType::VALIDATE_REPORT)
            ->where('ij.status', IngestJobStatus::SUCCEEDED)
            ->get()
            ->map(fn (Report $report) => new AnalyzeReportJob($report));

        Bus::batch($jobs)->allowFailures()->dispatch();
    }
}
