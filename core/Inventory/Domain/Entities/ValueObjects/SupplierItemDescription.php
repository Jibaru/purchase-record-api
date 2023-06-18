<?php

namespace Core\Inventory\Domain\Entities\ValueObjects;

class SupplierItemDescription
{
    public readonly string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }
}
