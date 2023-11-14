@props(['for'])

@if(isset($for) && $errors->has($for))
    <span class="text-red-600 text-sm ml-1">{{ $errors->get($for)[0] }}</span>
@elseif(isset($slot))
    <span class="text-sm ml-1 text-gray-600">{{ $slot }}</span>
@endif
