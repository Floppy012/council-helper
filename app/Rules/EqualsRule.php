<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

readonly class EqualsRule implements ValidationRule
{
    public function __construct(
        protected mixed $expected,
        protected bool $ignoreCase = true,
    ) {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $expected = $this->expected;
        if (is_string($value) && $this->ignoreCase) {
            $value = Str::lower($value);
            $expected = Str::lower($this->expected);
        }

        if ($value !== $expected) {
            $fail(":attribute must equal {$expected}");
        }
    }
}
