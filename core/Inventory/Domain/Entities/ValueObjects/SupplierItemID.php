<?php

namespace Core\Inventory\Domain\Entities\ValueObjects;

use Core\Shared\Uuid;

class SupplierItemID
{
    public readonly string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function empty(): self
    {
        return new SupplierItemID(Uuid::make());
    }
}
