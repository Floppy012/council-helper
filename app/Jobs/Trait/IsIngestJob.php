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
            'job_id' => $this->getJobId(),
        ], ['report_id', 'type']);
    }

    protected function finish(bool $success, array $errors = null): void
    {
        IngestJob::upsert([
            'report_id' => $this->report->id,
            'type' => $this->type,
            'status' => $success ? IngestJobStatus::SUCCEEDED : IngestJobStatus::FAILED,
            'errors' => json_encode($errors),
        ], ['report_id', 'type']);
    }

    private function getJobId(): ?string
    {
        if (! $job = $this->job) {
            return null;
        }

        if (! property_exists($job, 'id')) {
            return null;
        }

        return $job->id;
    }
}
