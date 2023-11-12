@props(['progress', 'color' => 'bg-indigo-600'])
<div {{ $attributes->class('relative bg-dark-400 w-full rounded-md') }}>
    <div class="absolute {{$color}} h-full rounded-md transition-[max-width]" style="width: 100%; max-width: {{$progress}}%"></div>
</div>
