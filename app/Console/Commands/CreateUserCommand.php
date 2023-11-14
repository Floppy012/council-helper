<?php

namespace App\Console\Commands;

use App\Models\User;
use Hash;
use Illuminate\Console\Command;
use Str;
use Validator;

class CreateUserCommand extends Command
{
    protected $signature = 'create:user {email}';

    protected $description = 'Creates a new user';

    public function handle(): void
    {
        $validated = Validator::validate($this->arguments(), ['email' => 'email']);

        $existing = User::where('email', $validated['email'])->exists();
        if ($existing) {
            $this->error('User with that email already exists');

            return;
        }

        $password = Str::password(16);
        $user = User::create([
            'email' => $validated['email'],
            'name' => $validated['email'],
            'email_verified_at' => now(),
            'password' => Hash::make($password),
        ]);

        $this->info("User {$user->id} created with initial password: $password");
    }
}
