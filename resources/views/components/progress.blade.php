@props(['progress'])
<div {{ $attributes->class('relative bg-dark-400 w-full rounded-md') }}>
    <div class="absolute bg-indigo-600 h-full rounded-md" style="width: {{$progress}}%"></div>
</div>
