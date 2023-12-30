<?php

namespace App\Rules;

use Arr;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class UpgradeLevelRule implements DataAwareRule, ValidationRule
{
    private static array $MAX_UPGRADES = [
        // Amirdrassil
        1207 => [
            'raid-lfr' => 9559,
            'raid-normal' => 9567,
            'raid-heroic' => 9581,
            'raid-mythic' => 9576,
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

        $upgradeIds = self::$MAX_UPGRADES[$instanceId];
        if (! $upgradeIds) {
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
