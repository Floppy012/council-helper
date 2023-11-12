<div class="w-3/4">
    <div wire:poll="refresh" class="w-full">
        <span class="font-bold text-lg">
            @if($failed) Report analyzation failed @else Your report is being analyzed ...@endif
        </span>
        <x-progress class="h-[10px] my-1 w-1/2" progress="{{$progress}}" color="{{ $failed ? 'bg-red-400' : 'bg-indigo-400' }}" />
        <ul>
            @foreach($jobs as $job)
                <li>
                    <i class="{{$job['status']->iconCss()}} {{$job['status']->colorCss()}} fa-fw mr-2"></i>{{$job['type']->name()}}
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
