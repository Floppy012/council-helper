<?php

namespace App\Livewire;

use App\Models\Raid;
use Livewire\Component;

class RaidSelect extends Component
{
    public function render()
    {
        return view('livewire.raid-select')->with([
            'raids' => Raid::all(),
        ]);
    }
}
