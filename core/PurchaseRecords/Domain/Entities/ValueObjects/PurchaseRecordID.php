<?php

namespace Core\PurchaseRecords\Domain\Entities\ValueObjects;

use Core\Shared\Uuid;

class PurchaseRecordID
{
    public readonly string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function empty(): self
    {
        return new PurchaseRecordID(Uuid::make());
    }
}
