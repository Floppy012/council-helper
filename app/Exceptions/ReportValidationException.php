<?php

namespace App\Exceptions;

use Exception;

class ReportValidationException extends Exception
{
    public function __construct(public readonly array $errors = [])
    {
        parent::__construct('Report Validation Exception');
    }
}
