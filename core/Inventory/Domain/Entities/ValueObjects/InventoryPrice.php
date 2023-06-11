<?php

namespace Core\Inventory\Domain\Entities\ValueObjects;

class InventoryPrice
{
    public readonly float $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }
}
