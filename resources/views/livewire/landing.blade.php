<div class="w-1/2 m-auto flex items-center h-screen">
    <div wire:transition class="w-full">
        <div class="mb-10">
            <h3 class="text-2xl text-center">Submit your Droptimizer</h3>
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
                <x-form.button>
                    <x-spinner wire:loading />
                    <span wire:loading>Submitting</span>
                    <span wire:loading.remove>Submit</span>
                </x-form.button>
            </x-form.group>
            <x-form.hint for="reportUrl" />
        </form>
    </div>


</div>
