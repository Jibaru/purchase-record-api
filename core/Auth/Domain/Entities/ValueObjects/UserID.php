<?php

namespace Core\Auth\Domain\Entities\ValueObjects;

use Core\Shared\Uuid;

class UserID
{
    public readonly string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function empty(): self
    {
        return new self(Uuid::make());
    }
}
