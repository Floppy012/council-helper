<?php

namespace App\Livewire\Forms\Acp;

use Livewire\Attributes\Rule;
use Livewire\Form;

class CreateTeamForm extends Form
{
    #[Rule('required|string|min:1|unique:teams,name', as: 'Team Name')]
    public string $teamName = '';

    #[Rule('nullable|string', as: 'WoWAudit Secret')]
    public ?string $wowauditSecret = null;
}
