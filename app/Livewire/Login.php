<?php

namespace App\Livewire;

use Auth;
use Illuminate\Http\Request;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Login extends Component
{
    #[Rule('required|string|email')]
    public ?string $username = null;

    #[Rule('required|string|min:1')]
    public ?string $password = null;

    public bool $failed = false;

    public function login(Request $request): void
    {
        $this->validate();

        if (Auth::attempt(['name' => $this->username, 'password' => $this->password])) {
            $request->session()->regenerate();
            $this->failed = false;
            $this->redirect(route('admin.dashboard'));

            return;
        }

        $this->failed = true;
    }

    public function render(Request $request)
    {
        return view('livewire.login');
    }
}
