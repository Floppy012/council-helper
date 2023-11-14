<?php

namespace App\Jobs;

use App\Models\Character;
use App\Models\Team;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WowAuditTeamSyncJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly Team $team,
    ) {
    }

    /**
     * @throws RequestException
     */
    public function handle(): void
    {
        $response = Http::withUserAgent('council-helper')
            ->withHeader('Authorization', $this->team->wowaudit_secret)
            ->acceptJson()
            ->get('https://wowaudit.com/v1/characters')
            ->throw();

        $characters = collect($response->json());

        Character::upsert($characters->map(fn (array $char) => [
            'name' => $char['name'],
            'realm' => Str::lower($char['realm']),
            'region' => 'eu', // TODO don't hardcode
        ])->all(), ['name', 'realm', 'region']);

        $charIdentifiers = $characters->map(fn (array $char) => "{$char['name']}-".Str::lower($char['realm'])
        );

        $this->team->characters()->sync(
            Character::whereIn(\DB::raw('CONCAT(name, \'-\', realm)'), $charIdentifiers)->pluck('id')
        );

    }
}
