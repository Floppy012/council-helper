<?php

namespace App\Enum;

enum IngestJobStatus: string
{
    case PENDING = 'pending';
    case PROGRESSING = 'progressing';
    case SUCCEEDED = 'succeeded';
    case FAILED = 'failed';

    public function iconCss(): string
    {
        return match ($this) {
            IngestJobStatus::PENDING => 'fas fa-circle',
            IngestJobStatus::PROGRESSING => 'fas fa-circle-notch animate-spin',
            IngestJobStatus::SUCCEEDED => 'fas fa-check',
            IngestJobStatus::FAILED => 'fas fa-x',
        };
    }

    public function colorCss(): string
    {
        return match ($this) {
            IngestJobStatus::PENDING => 'text-dark-400',
            IngestJobStatus::PROGRESSING => 'text-yellow-400',
            IngestJobStatus::SUCCEEDED => 'text-green-400',
            IngestJobStatus::FAILED => 'text-red-400',
        };
    }
}
