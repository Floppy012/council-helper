<div class="relative p-10 h-screen overflow-scroll" x-data="{ searchItems: '', searchPlayers: '' }">
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

            <span class="p-1">{{ $encounter->name ?? 'All Encounters' }}</span>
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
            <div class="ml-auto flex flex-nowrap text-sm w-4/12 gap-x-4">
                <div class="w-1/3">
                    <x-form.select name="filterTeamId" wire:model.live="filterTeamId">
                        <option value="" selected>All teams</option>
                        @foreach($teams as $team)
                            <option value="{{$team->public_id}}">{{$team->name}}</option>
                        @endforeach
                    </x-form.select>
                </div>

                <div class="w-1/3">
                    <x-form.input placeholder="Search Items ..." name="searchItems" size="sm" x-model="searchItems"/>
                </div>

                <div class="w-1/3">
                    <x-form.input placeholder="Search Players ..." name="searchPlayers" size="sm" x-model="searchPlayers"/>
                </div>

            </div>
        </div>
    </div>
    <div class="relative">
        @forelse($this->loot as $item)
            <div x-transition x-show="!searchItems || @js(strtolower($item->name)).includes(searchItems.toLowerCase())"
                 class="w-full p-5 rounded-md my-4 bg-dark-400/90 backdrop-blur shadow-md">
                <div class="flex items-center">
                    <a href="#" data-wowhead="item={{$item->blizzard_item_id}}">
                        <img class="inline" alt="item symbol"
                             src="https://wow.zamimg.com/images/wow/icons/medium/{{$item->icon_slug}}.jpg"/>
                        <span class="ml-2 font-bold">{{ $item->name }}</span>
                    </a>
                </div>
                <div class="max-h-[30vh] overflow-y-auto mt-2">
                    @if ($itemSimResults = $simResults->get($item->id))
                        <table class="sim-table table-auto">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Class / Spec</th>
                                <th>Sim Date</th>
                                <th class="!text-right">Base DPS</th>
                                <th class="!text-right">Item DPS</th>
                                <th class="!text-right">Gain</th>
                                <th class="!text-right">Gain %</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($itemSimResults as $simResult)
                                    @php
                                        $gainPerc = ($simResult->mean_gain / $simResult->analyzedReport->dps_mean) * 100;
                                        $stateClass = $gainPerc > 0 ? 'gain' : ($gainPerc === 0 ? 'same' : 'loss');
                                        $colorClass = $gainPerc > 0 ? 'text-green-500' : 'text-red-500';
                                    @endphp
                                    <tr x-transition x-show="!searchPlayers || normalizeString(@js(strtolower($simResult->analyzedReport->character->name))).includes(normalizeString(searchPlayers.toLowerCase()))">
                                        <td>
                                            <a href="{{ $simResult->analyzedReport->report->url }}"><img src="https://www.raidbots.com/favicon-16x16.png" /></a>
                                        </td>
                                        <td>
                                            {{$simResult->analyzedReport->character->name}}
                                            @if ($simResult->item->catalyst)
                                                <span class="cat"></span>
                                            @endif
                                        </td>
                                        <td>{{ $simResult->analyzedReport->spec_id->name() }}</td>
                                        <td>{{$simResult->analyzedReport->simulated_at->diffForHumans() }}</td>
                                        <td class="!text-right">{{ number_format($simResult->analyzedReport->dps_mean, 2) }} DPS</td>
                                        <td class="!text-right">{{ number_format($simResult->mean, 2) }} DPS</td>
                                        <td class="!text-right {{$colorClass}} {{$stateClass}}">{{ number_format($simResult->mean_gain, 2) }} DPS</td>
                                        <td class="!text-right {{$colorClass}} {{$stateClass}}">{{ number_format($gainPerc, 2) }} %</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center font-bold">No simulations for this item/difficulty</div>
                    @endif
                </div>

            </div>
        @empty
            <div class="text-lg font-bold">No loot recorded for this encounter</div>
        @endforelse

    </div>

    <script>const whTooltips = {colorLinks: false, iconizeLinks: false, renameLinks: false};</script>
    <script src="https://wow.zamimg.com/js/tooltips.js"></script>
    <script>
        function normalizeString(str) {
            return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
        }
    </script>
</div>
