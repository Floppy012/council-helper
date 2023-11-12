<?php

namespace App\Enum;

enum IngestJobType: string
{
    case DOWNLOAD_REPORT = 'download_report';
    case VALIDATE_REPORT = 'validate_report';
    case ANALYZE_REPORT = 'analyze_report';

    public function name(): string
    {
        return match ($this) {
            IngestJobType::DOWNLOAD_REPORT => 'Download Report',
            IngestJobType::VALIDATE_REPORT => 'Validate Report',
            IngestJobType::ANALYZE_REPORT => 'Analyze Report',
        };
    }
}
