<?php

namespace App\Livewire;

use App\Models\Raid;
use Livewire\Attributes\Locked;
use Livewire\Component;

class EncounterSelect extends Component
{
    #[Locked]
    public Raid $raid;

    public function mount(Raid $raid): void
    {
        $this->raid = $raid;
        $this->raid->load(['encounters']);
    }

    public function render()
    {
        return view('livewire.encounter-select');
    }
}
