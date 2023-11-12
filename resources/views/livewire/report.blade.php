<div class="h-screen flex items-center justify-center">
    @if($report->batch_id)
        <div wire:poll class="w-1/2">
            <livewire:report-loading :report="$report" />
        </div>
    @else
        <h3 class="text-2xl text-center">Your report has been submitted 🎉</h3>
    @endif
</div>

