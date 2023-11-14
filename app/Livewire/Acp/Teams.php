<?php

namespace App\Livewire\Acp;

use App\Jobs\WowAuditTeamSyncJob;
use App\Livewire\Forms\Acp\CreateTeamForm;
use App\Models\Team;
use Livewire\Component;

class Teams extends Component
{
    public CreateTeamForm $createTeamForm;

    public bool $createModalOpen;

    public function createTeam(): void
    {
        $this->validate();

        Team::create([
            'name' => $this->createTeamForm->teamName,
            'wowaudit_secret' => $this->createTeamForm->wowauditSecret ?? null,
        ]);

        $this->createModalOpen = false;
        $this->createTeamForm->reset();
    }

    public function deleteTeam(string $id): void
    {
        Team::where('public_id', $id)->delete();
    }

    public function syncTeam(string $id): void
    {
        $team = Team::where('public_id', $id)->first();
        if (! $team || ! $team->wowaudit_secret) {
            return;
        }

        WowAuditTeamSyncJob::dispatch($team);
    }

    public function render()
    {
        return view('livewire.acp.teams')
            ->with('teams', Team::all());
    }
}
