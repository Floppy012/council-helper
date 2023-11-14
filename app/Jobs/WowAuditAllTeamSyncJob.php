<?php

namespace App\Jobs;

use App\Models\Team;
use Bus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WowAuditAllTeamSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {
        $jobs = Team::whereNotNull('wowaudit_secret')
            ->get()
            ->map(fn (Team $team) => new WowAuditTeamSyncJob($team));

        Bus::batch($jobs);
    }
}
