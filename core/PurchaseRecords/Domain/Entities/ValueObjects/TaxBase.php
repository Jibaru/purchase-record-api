<?php

namespace Core\PurchaseRecords\Domain\Entities\ValueObjects;

class TaxBase
{
    public const FIRST_ORDER = 14;
    public const SECOND_ORDER = 16;
    public const THIRD_ORDER = 18;

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
