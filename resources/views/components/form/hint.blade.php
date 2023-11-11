@props(['for'])

@if(isset($for) && $errors->has($for))
    <span class="text-red-700 text-sm ml-2.5">{{ $errors->get($for)[0] }}</span>
@elseif(isset($slot))
    <span class="text-sm ml-2.5 text-gray-600">{{ $slot }}</span>
@endif
