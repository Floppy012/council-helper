<?php

namespace App\Jobs\Trait;

use App\Enum\IngestJobStatus;
use App\Enum\IngestJobType;
use App\Models\IngestJob;
use App\Models\Report;

trait IsIngestJob
{
    protected readonly IngestJobType $type;

    protected readonly Report $report;

    protected function create(): void
    {
        IngestJob::upsert([
            'report_id' => $this->report->id,
            'type' => $this->type,
            'status' => IngestJobStatus::PENDING,
        ], ['report_id', 'type']);
    }

    protected function begin(): void
    {
        IngestJob::upsert([
            'report_id' => $this->report->id,
            'type' => $this->type,
            'status' => IngestJobStatus::PROGRESSING,
            'job_id' => $this->job->getJobId(),
        ], ['report_id', 'type']);
    }

    protected function finish(bool $success, array $errors = null): void
    {
        IngestJob::upsert([
            'report_id' => $this->report->id,
            'type' => $this->type,
            'status' => $success ? IngestJobStatus::SUCCEEDED : IngestJobStatus::FAILED,
            'errors' => $errors,
        ], ['report_id', 'type']);
    }
}
