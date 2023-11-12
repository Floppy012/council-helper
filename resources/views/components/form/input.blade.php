@props(['size', 'name'])

@php
$sizeClasses = match ($size) {
    'lg' => 'text-lg',
    '2xl' => 'text-2xl',
    default => '',
};

$ringColor = $errors->has($name) ? 'border-red-900 focus:border-red-500' : 'border-dark-400 focus:border-indigo-800';
@endphp


<div class="w-full">
    <input name="{{$name}}" {{ $attributes->merge([
    'class' => "
        block w-full bg-dark-800 text-gray-300 rounded-md py-2 px-3 shadow-sm border
        $ringColor placeholder:text-dark-200 border-inset
        focus:outline-none $sizeClasses
    "
    ]) }} />
</div>

