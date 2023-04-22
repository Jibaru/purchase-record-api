<?php

namespace Core\PurchaseRecords\Domain\Entities\ValueObjects;

class CorrelativeAccountingEntryNumber
{
    public const ORDER = 2;

    public readonly string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function make(): self
    {
        return new self(
            'A-01'
        );
    }
}
