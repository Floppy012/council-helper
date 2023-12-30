<?php

namespace App\Http\Controllers;

use App\Models\Report;

class SimCController extends Controller
{
    public function __invoke(Report $report)
    {
        $simc = $report->rawData()->select('data->simbot->meta->rawFormData->text as simc')
            ->get()
            ->first()
            ->getAttribute('simc');

        return response($simc, 200)
            ->header('Content-Type', 'text/plain');
    }
}
