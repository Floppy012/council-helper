<?php

namespace App\Livewire\ACP;

use Illuminate\Http\Request;
use Livewire\Component;

class Dashboard extends Component
{
    public function mount(Request $request)
    {
    }

    public function render()
    {
        return view('livewire.acp.dashboard');
    }
}
