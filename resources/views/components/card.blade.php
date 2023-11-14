@props(['class'])
<div class="w-full p-5 rounded-md my-4 bg-dark-400/90 backdrop-blur shadow-md {{ $class ?? '' }}">
    {{$slot}}
</div>
