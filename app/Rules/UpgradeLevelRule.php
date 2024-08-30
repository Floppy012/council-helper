<?php

namespace App\Rules;

use Arr;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class UpgradeLevelRule implements DataAwareRule, ValidationRule
{
    private static array $MAX_UPGRADES = [
        1273 => [
            'raid-lfr' => 10274,
            'raid-normal' => 10266,
            'raid-heroic' => 10256,
            'raid-mythic' => 10299,
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
