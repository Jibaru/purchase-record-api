<?php

namespace Core\Auth\Domain\Entities\ValueObjects;

use InvalidArgumentException;

class UserEmail
{
    public readonly string $value;

    /**
     * @param string $value
     * @throws InvalidArgumentException
     */
    public function __construct(string $value)
    {
        if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('invalid email');
        }

        $this->value = $value;
    }
}
