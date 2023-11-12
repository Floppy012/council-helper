<div class="p-10">
    <div class="text-4xl mb-5">Select Raid</div>
    <div class="grid grid-cols-4">
        @foreach($raids as $raid)
            <a
                class="rounded-md shadow-md hover:shadow-2xl h-[180px] cursor-pointer transition-[box-shadow] bg-center bg-cover flex items-end justify-center"
                style="background-image: url({{asset("images/blizzard/raids/{$raid->slug}/select.png")}})"
                href="{{ route('encounter-select', ['raid' => $raid->slug]) }}"
            >
                <div
                    class="w-full h-16 text-4xl font-bold flex items-center justify-center text-shadow"
                    style="text-shadow: 0 0 20px rgba(0, 0, 0, .75)"
                >
                    {{ $raid->name }}
                </div>
            </a>
        @endforeach

    </div>
</div>
