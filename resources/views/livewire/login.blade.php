<div class="w-full h-screen flex items-center justify-center">
    <div class="w-[20%] shadow-md bg-dark-400 rounded-md p-6">
        <div class="text-xl text-center mb-4">Login</div>
        <div x-show="$wire.failed" x-transition class="alert alert-error">Invalid credentials</div>
        <form class="grid gap-y-2" wire:submit="login">
            <x-form.input wire:model="username" type="text" name="username" placeholder="Username" size="sm"/>
            <x-form.hint for="username" />
            <x-form.input wire:model="password" type="password" name="password" placeholder="Password" size="sm"/>
            <x-form.hint for="password" />
            <x-form.button class="justify-center">
                <x-spinner wire:loading />
                <span wire:loading>Logging in ...</span>
                <span wire:loading.remove>Login</span>
            </x-form.button>
        </form>
    </div>
</div>
