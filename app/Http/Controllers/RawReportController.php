<?php

namespace App\Http\Controllers;

use App\Models\Report;

class RawReportController extends Controller
{
    public function __invoke(Report $report)
    {
        $data = $report->loadMissing(['rawData'])->rawData->data;

        return response()->json($data);
    }
}
