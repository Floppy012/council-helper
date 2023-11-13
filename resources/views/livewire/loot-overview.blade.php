<div class="relative p-10 h-screen overflow-scroll" x-data="{ search: '' }">
    <div
            class="fixed z-0 w-full h-full top-0 left-0 blur-md bg-cover bg-center"
            style="background-image: url({{asset("images/blizzard/raids/{$raid->slug}/background.jpg")}})"
    ></div>

    <div class="sticky z-10 top-0 left-0 w-full">
        <div class="flex w-full p-5 rounded-md bg-dark-400/90 backdrop-blur shadow-md text-2xl font-bold items-center">
            <a class="rounded hover:bg-white/10 mr-1 cursor-pointer p-1"
               href="{{route('encounter-select', ['raid' => $raid->slug])}}">
                <i class="fas fa-chevron-left fa-fw"></i>
            </a>

            <span class="p-1">{{ $encounter->name }}</span>
            <div class="flex w-[20%] gap-x-1 ml-2">
                @foreach(\App\Enum\RaidDifficulty::cases() as $raidDifficulty)
                    <input
                            wire:model.live="difficulty"
                            id="diff_{{$raidDifficulty}}"
                            type="radio"
                            value="{{ $raidDifficulty }}"
                            class="appearance-none"/>
                    <label
                            for="diff_{{$raidDifficulty}}"
                            class="
                        text-base p-2 rounded block w-1/4 text-center cursor-pointer hover:bg-white/10 transition-colors
                        @if ($raidDifficulty === $difficulty) bg-white/20 @endif
                        "
                    >
                        {{$raidDifficulty->name()}}
                    </label>

                @endforeach

            </div>
            <div class="ml-auto w-[15%]">
                <x-form.input placeholder="Search ..." name="search" size="sm" x-model="search"/>
            </div>
        </div>
    </div>
    <div class="relative">
        @forelse($loot as $item)
            <div x-transition x-show="!search || '{{strtolower($item->name)}}'.includes(search.toLowerCase())"
                 class="w-full p-5 rounded-md my-4 bg-dark-400/90 backdrop-blur shadow-md">
                <div class="flex items-center">
                    <a href="#" data-wowhead="item={{$item->blizzard_item_id}}">
                        <img class="inline" alt="item symbol"
                             src="https://wow.zamimg.com/images/wow/icons/medium/{{$item->icon_slug}}.jpg"/>
                        <span class="ml-2 font-bold">{{ $item->name }}</span>
                    </a>
                </div>
                <div class="max-h-[30vh] overflow-y-auto mt-2">
                    <table class="sim-table table-fixed">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Sim Date</th>
                            <th>Base DPS</th>
                            <th>Item DPS</th>
                            <th>Gain</th>
                            <th>Gain %</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($simResults->get($item->id) ?? [] as $simResult)
                            @php
                                $gainPerc = ($simResult->mean_gain / $simResult->analyzedReport->dps_mean) * 100;
                                $stateClass = $gainPerc > 0 ? 'gain' : ($gainPerc === 0 ? 'same' : 'loss');
                                $colorClass = $gainPerc > 0 ? 'text-green-500' : 'text-red-500';
                            @endphp
                            <tr>
                                <td>
                                    {{$simResult->analyzedReport->character->name}}
                                    @if ($simResult->item->catalyst)
                                        <span class="cat"></span>
                                    @endif
                                </td>
                                <td>{{$simResult->analyzedReport->simulated_at->diffForHumans() }}</td>
                                <td>{{ number_format($simResult->analyzedReport->dps_mean, 2) }} DPS</td>
                                <td>{{ number_format($simResult->mean, 2) }} DPS</td>
                                <td class="{{$colorClass}} {{$stateClass}}">{{ number_format($simResult->mean_gain, 2) }} DPS</td>
                                <td class="{{$colorClass}} {{$stateClass}}">{{ number_format($gainPerc, 2) }} %</td>
                            </tr>
                        @empty
                            nope
                        @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        @empty
            <div class="text-lg font-bold">No loot recorded for this encounter</div>
        @endforelse

    </div>

    <script>const whTooltips = {colorLinks: false, iconizeLinks: false, renameLinks: false};</script>
    <script src="https://wow.zamimg.com/js/tooltips.js"></script>
</div>
