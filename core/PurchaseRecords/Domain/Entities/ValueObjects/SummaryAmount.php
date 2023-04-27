<?php

namespace Core\PurchaseRecords\Domain\Entities\ValueObjects;

class SummaryAmount
{
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
