<div class="w-1/2 m-auto flex items-center h-screen">
    <div
            class="absolute z-0 w-full h-full top-0 left-0 blur-md bg-cover bg-center"
            style="background-image: url({{asset("images/blizzard/raids/amirdrassil/background.jpg")}})"
    ></div>
    <div class="relative z-10 w-full">
        <div class="mb-8">
            <h3 class="text-2xl text-center font-bold">Submit your Droptimizer</h3>
            <div class="mt-1 text-base text-gray-400 text-center">Patchwerk &middot; 1 Boss &middot; 5 Minutes &middot; SimC Nightly &middot; Optimal Raid Buffs</div>
        </div>

        <form wire:submit="submit">
            <x-form.group class="w-full">
                <x-form.input
                    name="reportUrl"
                    size="lg"
                    placeholder="https://raidbots.com/simbot/report/..."
                    wire:model="reportUrl"
                    wire:keydown.enter.debounce.1s="submit"
                />
                <x-form.button class="bg-green-600 justify-center">
                    <span class="inline-flex items-center" wire:loading>Submitting</span>
                    <span class="inline-flex items-center" wire:loading.remove><i class="fas fa-paper-plane mr-3"></i>Submit</span>
                </x-form.button>
            </x-form.group>
            <x-form.hint for="reportUrl" />
        </form>
    </div>

    @teleport('body')
    <div class="absolute top-0 right-0 p-8 flex justify-center">
        <a class="button text-sm mr-3" href="{{ route('encounter-select', ['raid' => 'vault-of-the-incarnates']) }}">Vault of the Incarnates</a>
        <a class="button text-sm mr-3" href="{{ route('encounter-select', ['raid' => 'aberrus']) }}">Aberrus</a>
        <a class="button text-sm" href="{{ route('encounter-select', ['raid' => 'amirdrassil']) }}">Amirdrassil</a>
    </div>
    @endteleport


</div>
