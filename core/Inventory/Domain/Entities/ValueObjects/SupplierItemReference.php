<?php

namespace Core\Inventory\Domain\Entities\ValueObjects;

use Core\Shared\Uuid;

class SupplierItemReference
{
    public readonly string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function make(SupplierItemCode $code, SupplierItemDescription $description): self
    {
        $value = hash('md5', $code->value . '&' . $description->value);

        return new SupplierItemReference($value);
    }
}
