<div class="relative p-10">
    <div>
        <p class="text-4xl">{{ $character->name }}</p>
        <span class="text-lg">
             @if($lastRace)
                {{ $lastRace->name() }}
            @endif
            @if($lastClass)
                <span style="color: {{$lastClass->hexColor()}};">{{$lastClass->name()}}</span>
            @endif
        </span>
    </div>
    <div class="mt-3">
        <h1 class="text-gray-500 text-xl">Sim DPS History</h1>
        <div class="w-full h-[25vh]">
            <canvas id="dpsHistory"></canvas>
        </div>
    </div>
    <div class="mt-3">
        <h1 class="text-gray-500 text-xl">Report History</h1>
        <table class="min-w-full divide-y divide-gray-700">
            <thead>
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-0">Date</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Raid</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Difficulty</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Class & Spec</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">SimDPS</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @foreach($this->analyzedReports as $report)
                    <tr>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-0">{{ $report->simulated_at->format('d.m.Y H:i')}}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">{{ $report->raid->name }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">{{ $report->raid_difficulty->name() }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">{{ $report->spec_id->name() }}</td>

                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">{{ number_format($report->dps_mean, 2) }} DPS</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300 flex flex-row gap-x-3">
                            <a href="{{ $report->report->url }}"><img alt="raidbots" src="https://www.raidbots.com/favicon-16x16.png" /></a>
                            <a href="javascript:" onclick="copySimc('{{ route('simc', ['report' => $report->report->public_id]) }}')"><i class="fas fa-copy"></i></a>
                            <a href="{{ route('raw-report', ['report' => $report->report->public_id]) }}" target="_blank"><i class="fas fa-file-code"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>

    </div>

    <script>
        const dpsHistoryDatasets = @json($this->dpsHistoryDatasets);

        async function copySimc(url) {
            const result = await fetch(url);
            const body = await result.text();

            void navigator.clipboard.writeText(body);
        }
    </script>
    @vite('resources/js/character-profile.js')
</div>
