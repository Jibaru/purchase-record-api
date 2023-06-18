<?php

namespace Core\Inventory\Domain\Dtos;

class SimpleSupplierDto
{
    public readonly string $reference;
    public readonly string $type;
    public readonly string $number;
    public readonly string $denomination;
    public readonly float $totalItems;
    public readonly float $totalPrice;

    public function __construct(
        string $reference,
        string $type,
        string $number,
        string $denomination,
        float $totalItems,
        float $totalPrice
    ) {
        $this->reference = $reference;
        $this->type = $type;
        $this->number = $number;
        $this->denomination = $denomination;
        $this->totalItems = $totalItems;
        $this->totalPrice = $totalPrice;
    }
}
