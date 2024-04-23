<div class="w-3/4">
    <div
            class="absolute z-0 w-full h-full top-0 left-0 blur-md bg-cover bg-center opacity-40"
            style="background-image: url({{asset("images/blizzard/background.jpg")}})"
    ></div>
    <div wire:poll="refresh" class="relative z-10 w-full bg-dark-600 p-5 rounded-md shadow-md">
        <span class="font-bold text-lg">
            @if($failed) Report analyzation failed @else Your report is being analyzed ...@endif
        </span>
        <x-progress class="h-[10px] my-1 w-1/2" progress="{{$progress}}" color="{{ $failed ? 'bg-red-400' : 'bg-indigo-400' }}" />
        <ul>
            @foreach($jobs as $job)
                <li>
                    @php($postClass = (!$failed && $job['status'] === \App\Enum\IngestJobStatus::PENDING) ? 'animate-pulse' : '')
                    <i class="{{$job['status']->iconCss()}} {{$job['status']->colorCss()}} {{ $postClass }} fa-fw mr-2"></i>{{$job['type']->name()}}
                    @if ($job['errors'])
                        <ul class="text-red-500 ml-7">
                            @foreach($job['errors'] as $err)
                                <li>{{$err}}</li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>
