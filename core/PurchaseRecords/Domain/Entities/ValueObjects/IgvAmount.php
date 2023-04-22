<?php

namespace Core\PurchaseRecords\Domain\Entities\ValueObjects;

class IgvAmount
{
    public const FIRST_ORDER = 15;
    public const SECOND_ORDER = 17;
    public const THIRD_ORDER = 19;

    public readonly float $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }

    public static function make(float $value): self
    {
        return new self($value);
    }
}
