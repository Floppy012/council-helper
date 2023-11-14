<div class="h-screen flex items-center justify-center">
    @if($report->batch_id)
        <div wire:poll class="w-1/2">
            <livewire:report-loading :report="$report" />
        </div>
    @else
        <div>
            <h3 class="text-3xl text-center">Your report has been submitted 🎉</h3>
            <div class="flex justify-around mt-4">
                <a class="button w-[45%] text-center text-sm" href="{{ route('landing') }}">Submit another report</a>
                <a class="button w-[45%] text-center text-sm" href="{{ route('encounter-select', ['raid' => 'amirdrassil']) }}">Show current raid</a>
            </div>
        </div>
    @endif
</div>

