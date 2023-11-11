<div>
    @if($loading)
        <div wire:poll="refreshLoadingState" class="w-1/2 m-auto flex items-center h-screen">
            <div wire:transition class="w-3/4">
                <span class="font-bold text-lg">Your report is being analyzed ...</span>
                <x-progress class="h-[10px] my-1 w-1/2" progress="10"/>
                <ul>
                    <li><i class="fas fa-check mr-2 text-green-400 fa-fw"></i>Test</li>
                    <li><i class="fas fa-circle-notch mr-2 text-yellow-400 animate-spin fa-fw"></i>Test</li>
                    <li><i class="fas fa-x mr-2 text-red-400 fa-fw"></i>Test</li>
                </ul>
            </div>
        </div>
    @endif
</div>

