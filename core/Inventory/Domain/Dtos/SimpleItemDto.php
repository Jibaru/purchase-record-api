<?php

namespace Core\Inventory\Domain\Dtos;

class SimpleItemDto
{
    public readonly string $reference;
    public readonly string $code;
    public readonly string $description;
    public readonly float $totalQuantity;
    public readonly float $unitPriceAverage;

    public function __construct(
        string $reference,
        string $code,
        string $description,
        float $totalQuantity,
        float $unitPriceAverage
    ) {
        $this->reference = $reference;
        $this->code = $code;
        $this->description = $description;
        $this->totalQuantity = $totalQuantity;
        $this->unitPriceAverage = $unitPriceAverage;
    }
}
