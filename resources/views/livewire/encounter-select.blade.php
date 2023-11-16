<div class="relative p-10 h-screen">
    <div
        class="absolute z-0 w-full h-full top-0 left-0 blur-md bg-cover bg-center"
        style="background-image: url({{asset("images/blizzard/raids/{$raid->slug}/background.jpg")}})"
    ></div>
    <div class="z-10 relative">
        <div class="text-4xl">Select Encounter</div>
        <div class="text-2xl text-gray-400 mb-5">{{ $raid->name }}</div>

        <div class="grid grid-cols-5 gap-5">
            <a
                class="h-[200px] rounded-md shadow-lg hover:shadow-2xl transition-[box-shadow] cursor-pointer bg-dark-400 !bg-center !bg-cover flex items-end p-5"
                style="
                        background: radial-gradient(at center 10%, transparent 40%, black),
                                    url({{asset("images/blizzard/raids/{$raid->slug}/encounters/all.jpg")}})
                    "
                href="{{route('encounter', ['raid' => $raid->slug, 'encounterSlug' => 'all'])}}"
            >
                <div class="flex items-center justify-center text-xl font-bold uppercase">All Encounters</div>
            </a>
            @foreach($raid->encounters as $encounter)
                <a
                    class="h-[200px] rounded-md shadow-lg hover:shadow-2xl transition-[box-shadow] cursor-pointer bg-dark-400 !bg-center !bg-cover flex items-end p-5"
                    style="
                        background: radial-gradient(at center 10%, transparent 40%, black),
                                    url({{asset("images/blizzard/raids/{$raid->slug}/encounters/{$encounter->slug}.jpg")}})
                    "
                    href="{{route('encounter', ['raid' => $raid->slug, 'encounterSlug' => $encounter->slug])}}"
                >
                    <div class="flex items-center justify-center text-xl font-bold uppercase">{{ $encounter->name }}</div>
                </a>
            @endforeach

        </div>
    </div>
</div>
