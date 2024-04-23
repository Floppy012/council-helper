<div>
    @if($report->batch_id)
        <div wire:poll class="h-screen w-full flex items-center justify-center">
            <div class="w-1/2 flex items-center justify-center">
                <livewire:report-loading :report="$report" />
            </div>
        </div>
    @else
        <div class="h-screen flex items-center justify-center">
            <div
                class="absolute z-0 w-full h-full top-0 left-0 blur-md bg-cover bg-center opacity-40"
                style="background-image: url({{asset("images/blizzard/background.jpg")}})"
            ></div>
            <div class="z-10">
                <div>
                    <h3 class="text-3xl text-center">Your report has been submitted 🎉</h3>
                    <div class="flex justify-around mt-4">
                        <a class="button w-[45%] text-center text-sm" href="{{ route('landing') }}">Submit another report</a>
                        @if ($raidSlug = $report?->analyzedReport?->raid?->slug)
                            <a class="button w-[45%] text-center text-sm" href="{{ route('encounter-select', ['raid' => $raidSlug]) }}">Show current raid</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>


