<?php

namespace App\Rules;

use Arr;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class UpgradeLevelRule implements DataAwareRule, ValidationRule
{
    private static array $MAX_UPGRADES = [
        // VotI
        1200 => [
            'raid-lfr-fated' => 10320,
            'raid-normal-fated' => 10334,
            'raid-heroic-fated' => 10338,
            'raid-mythic-fated' => 10338,
        ],
        // Amirdrassil
        1207 => [
            'raid-lfr-fated' => 10320,
            'raid-normal-fated' => 10334,
            'raid-heroic-fated' => 10338,
            'raid-mythic-fated' => 10338,
        ],
        // Aberrus
        1208 => [
            'raid-lfr-fated' => 10320,
            'raid-normal-fated' => 10334,
            'raid-heroic-fated' => 10338,
            'raid-mythic-fated' => 10338,
        ],
    ];

    /**
     * @var array<string, mixed>
     */
    protected array $data = [];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $instanceId = $this->data['simbot.meta.rawFormData.droptimizer.instance'];
        $difficulty = $this->data['simbot.meta.rawFormData.droptimizer.difficulty'];

        if (! array_key_exists($instanceId, self::$MAX_UPGRADES)) {
            $fail("Unknown raid id $instanceId");

            return;
        }

        $upgradeIds = self::$MAX_UPGRADES[$instanceId];
        if (! $upgradeIds) {
            return;
        }

        if (! array_key_exists($difficulty, $upgradeIds)) {
            $fail("Unknown raid difficulty $difficulty");

            return;
        }

        $expected = $upgradeIds[$difficulty];
        if (! $expected) {
            $fail("Unknown raid difficulty $difficulty");

            return;
        }

        if ($value !== $expected) {
            $fail('Sim does not contain max-upgraded items');
        }
    }

    public function setData(array $data): self
    {
        $this->data = Arr::dot($data);

        return $this;
    }
}
