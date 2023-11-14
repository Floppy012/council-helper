@props(['openProperty'])
@teleport('body')
    <div x-show="$wire['{{$openProperty}}']" class="absolute top-0 w-full h-screen bg-black/40 backdrop-blur" >
        <div x-show="$wire['{{$openProperty}}']" x-transition class="bg-dark-500 rounded-md shadow-md min-h-[25%] mt-[10%] w-[40%] mx-auto" @click.outside="$wire['{{$openProperty}}'] = false">
            {{$slot}}
        </div>
    </div>
@endteleport

