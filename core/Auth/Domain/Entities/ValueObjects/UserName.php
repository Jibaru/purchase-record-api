<?php

namespace Core\Auth\Domain\Entities\ValueObjects;

class UserName
{
    public readonly string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }
}
