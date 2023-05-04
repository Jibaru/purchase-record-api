<?php

namespace Core\Auth\Domain\Entities\ValueObjects;

use Illuminate\Support\Facades\Hash;

class UserPassword
{
    public string $hashedValue;

    private function __construct(string $hashedValue)
    {
        $this->hashedValue = $hashedValue;
    }

    public static function fromHashed(string $hashedValue): self
    {
        return new self($hashedValue);
    }

    public static function hashAndMake(string $value): self
    {
        return new self(Hash::make($value));
    }

    public function hasSameValueAs(string $value): bool
    {
        return Hash::check($value, $this->hashedValue);
    }
}
