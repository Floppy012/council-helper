<?php

namespace App;

use App\Models\Report;
use Illuminate\Foundation\Events\Dispatchable;

readonly class ReportSubmittedEvent
{
    use Dispatchable;

    public function __construct(
        public Report $report
    ) {
    }
}
